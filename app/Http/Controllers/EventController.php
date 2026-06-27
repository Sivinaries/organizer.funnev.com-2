<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Event;
use App\Models\Approval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $query = Event::query()->with('transactions');

        if ($user->level !== 'Admin') {
            $query->where('user_id', $user->id);
        }

        $events = $query->latest()->get();

        return view('event', compact('events'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'no_telpon' => 'required|string|max:15',
            'event' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:65535',
            'syarat' => 'required|string|max:65535',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'kategori_id' => 'required|exists:kategoris,id',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'img2' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'ktp' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data['user_id'] = $user->id;
        $data['status'] = 'Checking';
        $data['img'] = $this->storeFile($request, 'img', 'img');
        $data['img2'] = $this->storeFile($request, 'img2', 'img');
        $data['ktp'] = $this->storeFile($request, 'ktp', 'ktp');

        Approval::create($data);

        $this->logActivity(
            'Create Event',
            "User {$user->name} mengajukan event baru: {$data['event']}",
            $user->id
        );

        $this->clearCache($user->id);

        return redirect()->route('events')->with('success', 'Event successfully registered!');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('showevent', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $event = Event::findOrFail($id);

        // Check authorization
        if ($event->user_id !== $user->id && $user->level !== 'Admin') {
            return redirect()->route('events')->withErrors(['msg' => 'Unauthorized action.']);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'no_telpon' => 'required|string|max:15',
            'event' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:65535',
            'syarat' => 'required|string|max:65535',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        // Store old values for change detection
        $old = [
            'name' => $event->name,
            'event' => $event->event,
            'location' => $event->location,
            'description' => $event->description,
        ];

        // Handle image uploads
        if ($request->hasFile('img')) {
            $data['img'] = $this->storeFile($request, 'img', 'img');
        }
        if ($request->hasFile('img2')) {
            $data['img2'] = $this->storeFile($request, 'img2', 'img');
        }
        if ($request->hasFile('ktp')) {
            $data['ktp'] = $this->storeFile($request, 'ktp', 'ktp');
        }

        $event->update($data);

        // Detect what changed
        $changes = [];
        foreach ($old as $field => $value) {
            if (isset($data[$field]) && $old[$field] != $data[$field]) {
                $label = ucfirst(str_replace('_', ' ', $field));
                $changes[] = "{$label} changed from '{$old[$field]}' to '{$data[$field]}'";
            }
        }

        if ($changes || $request->hasFile('img') || $request->hasFile('img2') || $request->hasFile('ktp')) {
            $desc = "Update Event '{$event->event}': " . implode(', ', $changes);
            $this->logActivity('Update Event', $desc, $user->id);
        }

        $this->clearCache($user->id);

        return redirect()->route('events')->with('success', 'Event successfully updated!');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $event = Event::findOrFail($id);

        // Check authorization
        if ($event->user_id !== $user->id && $user->level !== 'Admin') {
            return redirect()->route('events')->withErrors(['msg' => 'Unauthorized action.']);
        }

        $name = $event->event;
        $event->delete();

        $this->logActivity(
            'Delete Event',
            "User {$user->name} menghapus event: {$name}",
            $user->id
        );

        $this->clearCache($user->id);

        return redirect()->route('events')->with('success', 'Event removed!');
    }

    private function storeFile(Request $request, string $fieldName, string $folder)
    {
        if ($request->hasFile($fieldName)) {

            $file = $request->file($fieldName);

            // Validate that the uploaded file is really an image
            if (!str_starts_with($file->getMimeType(), 'image/')) {
                throw ValidationException::withMessages([
                    $fieldName => "The uploaded file for {$fieldName} must be a valid image.",
                ]);
            }

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $fileName = $originalName . '-' . time() . '.jpg';

            $path = storage_path("app/public/$folder/$fileName");

            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            $manager = new ImageManager(new Driver());

            // Read the file
            $image = $manager->read($file->getPathname());

            // Optional resize
            if ($image->width() > 1280) {
                $image->scale(width: 1280);
            }

            // Save as compressed JPEG
            $image->toJpeg(70)->save($path);

            return "$folder/$fileName";
        }

        return null;
    }

    private function clearCache(int $userId): void
    {
        Cache::forget('events_all');
        Cache::forget("events_user_{$userId}");
        Cache::forget('acts');
    }

    private function logActivity(string $action, string $description, int $userId): void
    {
        Act::create([
            'user_id' => $userId,
            'action' => strtolower(str_replace(' ', '_', $action)),
            'description' => $description,
        ]);

        Cache::forget('acts');
    }
}
