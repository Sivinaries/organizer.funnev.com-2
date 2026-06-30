<table border="0" cellspacing="0" cellpadding="6"
    style="border-collapse: collapse; font-family: Calibri, Arial, sans-serif; font-size: 11px;">
    <thead>
        {{-- Judul --}}
        <tr>
            <th colspan="10"
                style="font-weight: bold; font-size: 18px; text-align: center; background-color: #f97316; color: #ffffff; padding: 10px; letter-spacing: 1px;">
                FUNNEV — LAPORAN TRANSAKSI
            </th>
        </tr>
        <tr>
            <th colspan="10"
                style="font-size: 11px; text-align: center; background-color: #fdba74; color: #7c2d12; padding: 4px;">
                @if ($start || $end)
                    Periode:
                    {{ $start ? \Carbon\Carbon::parse($start)->format('d M Y') : '...' }}
                    s/d
                    {{ $end ? \Carbon\Carbon::parse($end)->format('d M Y') : '...' }}
                @else
                    Semua Transaksi
                @endif
                &nbsp;|&nbsp; Dicetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
            </th>
        </tr>
        <tr>
            <th colspan="10" style="height: 6px; border: none;"></th>
        </tr>

        {{-- Header kolom --}}
        <tr>
            @php
                $thStyle = 'border: 1px solid #9a3412; font-weight: bold; background-color: #ea580c; color: #ffffff; text-align: center; vertical-align: middle; padding: 6px;';
            @endphp
            <th style="{{ $thStyle }} width: 35px;">No</th>
            <th style="{{ $thStyle }}">Tanggal</th>
            <th style="{{ $thStyle }}">No Order</th>
            <th style="{{ $thStyle }}">Pembeli</th>
            <th style="{{ $thStyle }}">Email</th>
            <th style="{{ $thStyle }}">Event</th>
            <th style="{{ $thStyle }}">Rincian Tiket</th>
            <th style="{{ $thStyle }}">Service</th>
            <th style="{{ $thStyle }}">Total</th>
            <th style="{{ $thStyle }}">Status</th>
        </tr>
    </thead>

    <tbody>
        @php
            $no = 1;
            $grandTotal = 0;
            $cell = 'border: 1px solid #d1d5db; vertical-align: top; padding: 5px;';
        @endphp

        @forelse ($transactions as $trx)
            @php
                $grandTotal += $trx->total_amount;
                $rowBg = $no % 2 === 0 ? '#fff7ed' : '#ffffff';

                $st = strtolower($trx->status);
                if ($st === 'settlement') {
                    $stColor = '#15803d'; $stBg = '#dcfce7';
                } elseif ($st === 'pending') {
                    $stColor = '#b45309'; $stBg = '#fef3c7';
                } else {
                    $stColor = '#b91c1c'; $stBg = '#fee2e2';
                }
            @endphp
            <tr style="background-color: {{ $rowBg }};">
                <td style="{{ $cell }} text-align: center;">{{ $no++ }}</td>
                <td style="{{ $cell }} white-space: nowrap;">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}</td>
                <td style="{{ $cell }} font-family: Consolas, monospace;">{{ $trx->no_order }}</td>
                <td style="{{ $cell }} font-weight: bold;">{{ $trx->user->name ?? '-' }}</td>
                <td style="{{ $cell }}">{{ $trx->user->email ?? '-' }}</td>
                <td style="{{ $cell }}">{{ $trx->event->event ?? '-' }}</td>
                <td style="{{ $cell }}">
                    @foreach ($trx->tickets as $tk)
                        {{ $tk->type }} ({{ $tk->pivot->qty }}x) = Rp {{ number_format($tk->pivot->subtotal, 0, ',', '.') }}@if (!$loop->last)<br>@endif
                    @endforeach
                </td>
                <td style="{{ $cell }} text-align: right; white-space: nowrap;">Rp {{ number_format($trx->total_service, 0, ',', '.') }}</td>
                <td style="{{ $cell }} text-align: right; font-weight: bold; white-space: nowrap;">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                <td style="{{ $cell }} text-align: center; color: {{ $stColor }}; background-color: {{ $stBg }}; font-weight: bold;">
                    {{ ucfirst($trx->status) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" style="{{ $cell }} text-align: center; color: #9ca3af; padding: 20px;">
                    Tidak ada transaksi pada rentang ini.
                </td>
            </tr>
        @endforelse

        {{-- Grand Total --}}
        <tr>
            <td colspan="8"
                style="border: 2px solid #9a3412; font-weight: bold; text-align: right; padding: 8px; background-color: #1f2937; color: #ffffff;">
                GRAND TOTAL ({{ $transactions->count() }} transaksi)
            </td>
            <td colspan="2"
                style="border: 2px solid #9a3412; font-weight: bold; text-align: right; padding: 8px; background-color: #f97316; color: #ffffff; white-space: nowrap;">
                Rp {{ number_format($grandTotal, 0, ',', '.') }}
            </td>
        </tr>
    </tbody>
</table>
