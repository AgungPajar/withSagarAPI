<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Club;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Vinkla\Hashids\Facades\Hashids;

class ExportExcelController extends Controller
{
    public function exportHarian(Request $request)
    {
        $date = $request->query('date');
        $hashId = $request->query('club_id');
        $decoded = Hashids::decode($hashId);

        if (count($decoded) === 0) {
            return response()->json(['message' => 'ID tidak valid'], 400);
        }

        $clubId = $decoded[0];
        $club = Club::find($clubId);

        if (!$club) {
            return response()->json(['message' => 'Ekskul tidak ditemukan'], 404);
        }

        $attendances = Attendance::with('student')
            ->where('club_id', $clubId)
            ->whereDate('date', $date)
            ->get();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $sheet = $spreadsheet->getActiveSheet();

        // Tambah logo
        if (file_exists(public_path('logoosis.png'))) {
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setPath(public_path('logoosis.png'));
            $drawing->setCoordinates('A1');
            $drawing->setHeight(80);
            $drawing->setWorksheet($sheet);
        }

        // Header sekolah
        $sheet->mergeCells('A1:H1')->setCellValue('A1', 'ORGANISASI DAN EKSTRAKURIKULER');
        $sheet->mergeCells('A2:H2')->setCellValue('A2', 'SMK NEGERI 1 GARUT');
        $sheet->mergeCells('A3:H3')->setCellValue('A3', 'Jl. Cimanuk No. 309 A Telp (0262) 233316 Garut 44151');
        $sheet->mergeCells('A4:H4')->setCellValue('A4', 'website : www.smknegeri1garut.sch.id e-mail : smkn1garut@gmail.com');
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:H2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:H4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A1:A2')->getFont()->setBold(true);
        $sheet->getStyle('A3:A4')->getFont();

        // Identitas ekskul
        $sheet->mergeCells('B6:C6')->setCellValue('B6', 'Nama Ekstrakurikuler :');
        $sheet->mergeCells('D6:E6')->setCellValue('D6', $club->name);
        $sheet->mergeCells('B7:C7')->setCellValue('B7', 'Tanggal :');
        $sheet->mergeCells('D7:E7')->setCellValue('D7', $date);

        // Header tabel
        $headers = ['No', 'Nama', 'Kelas', 'Status'];
        $col = 'B';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '9', $header);
            $col++;
        }

        $sheet->getStyle('B9:E9')->getFont()->setBold(true);
        $sheet->getStyle('B9:E9')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('B9:E9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Data siswa
        $row = 10;
        foreach ($attendances as $i => $att) {
            $sheet->setCellValue("B{$row}", $i + 1);
            $sheet->setCellValue("C{$row}", $att->student->name ?? '-');
            $sheet->setCellValue("D{$row}", $att->student->class ?? '-');
            $sheet->setCellValue("E{$row}", $att->status);
            $row++;
        }
        $sheet->getStyle("B10:E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("B10:E{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getColumnDimension('C')->setWidth(30);

        $sheet->getStyle('B9:E9')->getFont()->setBold(true);
        $sheet->getStyle('B9:E9')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('B9:E9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $fileName = 'rekap-' . $club->name . '-' . $date . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    public function exportBulanan(Request $request)
    {
        $clubIdHash = $request->query('club_id');
        $from = $request->query('from_date');
        $to = $request->query('to_date');

        $decoded = Hashids::decode($clubIdHash);
        if (count($decoded) === 0) {
            return response()->json(['message' => 'ID tidak valid'], 400);
        }

        $clubId = $decoded[0];
        $club = \App\Models\Club::find($clubId);
        if (!$club) {
            return response()->json(['message' => 'Ekskul tidak ditemukan'], 404);
        }

        $data = \App\Models\Attendance::with('student')
            ->where('club_id', $clubId)
            ->whereBetween('date', [$from, $to])
            ->get();

        // Hitung jumlah hadir & tidak hadir
        $rekap = [];
        foreach ($data as $item) {
            $studentId = $item->student_id;
            if (!isset($rekap[$studentId])) {
                $rekap[$studentId] = [
                    'name' => $item->student->name ?? '-',
                    'class' => $item->student->class ?? '-',
                    'hadir' => 0,
                    'tidak_hadir' => 0,
                ];
            }

            if ($item->status === 'hadir') {
                $rekap[$studentId]['hadir']++;
            } else {
                $rekap[$studentId]['tidak_hadir']++;
            }
        }

        // Buat Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Kelas');
        $sheet->setCellValue('D1', 'Jumlah Hadir');
        $sheet->setCellValue('E1', 'Tidak Hadir');

        $row = 2;
        $i = 1;
        foreach ($rekap as $r) {
            $sheet->setCellValue("A{$row}", $i++);
            $sheet->setCellValue("B{$row}", $r['name']);
            $sheet->setCellValue("C{$row}", $r['class']);
            $sheet->setCellValue("D{$row}", $r['hadir']);
            $sheet->setCellValue("E{$row}", $r['tidak_hadir']);
            $row++;
        }

        $filename = "rekap-filter-{$from}-to-{$to}.xlsx";
        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
