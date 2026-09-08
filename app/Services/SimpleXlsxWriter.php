<?php

namespace App\Services;

use ZipArchive;

class SimpleXlsxWriter
{
    /**
     * Create a multi-sheet XLSX file from an associative array of sheet data with auto-calculated column widths.
     *
     * @param array<string, array<int, array<int, mixed>>> $sheetsData
     * @return string Raw binary XLSX file content
     */
    public static function createXlsx(array $sheetsData): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new ZipArchive();
        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Cannot create temporary zip file for XLSX generation.");
        }

        $sheetKeys = array_keys($sheetsData);
        $sheetCount = count($sheetKeys);

        // 1. [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $contentTypes .= '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">';
        $contentTypes .= '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>';
        $contentTypes .= '<Default Extension="xml" ContentType="application/xml"/>';
        $contentTypes .= '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>';
        $contentTypes .= '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>';
        for ($i = 1; $i <= $sheetCount; $i++) {
            $contentTypes .= '<Override PartName="/xl/worksheets/sheet' . $i . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
        }
        $contentTypes .= '</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // 2. _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $rels .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
        $rels .= '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>';
        $rels .= '</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // 3. xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $wbRels .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
        $wbRels .= '<Relationship Id="rIdStyles" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>';
        for ($i = 1; $i <= $sheetCount; $i++) {
            $wbRels .= '<Relationship Id="rIdSheet' . $i . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . $i . '.xml"/>';
        }
        $wbRels .= '</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // 4. xl/workbook.xml
        $wb = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $wb .= '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';
        $wb .= '<sheets>';
        $i = 1;
        $usedSheetNames = [];
        foreach ($sheetKeys as $sheetName) {
            // Remove characters forbidden in Excel sheet names: \ / ? * : [ ]
            $safeName = preg_replace('/[\\\\\/\\?\\*\\:\\[\\]]/', '', (string)$sheetName);
            $safeName = trim(mb_substr($safeName, 0, 31));
            if (empty($safeName)) {
                $safeName = 'Sheet' . $i;
            }
            $baseName = $safeName;
            $counter = 1;
            while (in_array(strtolower($safeName), $usedSheetNames)) {
                $safeName = trim(mb_substr($baseName, 0, 27)) . " ($counter)";
                $counter++;
            }
            $usedSheetNames[] = strtolower($safeName);


            $xmlName = htmlspecialchars($safeName, ENT_QUOTES, 'UTF-8');
            $wb .= '<sheet name="' . $xmlName . '" sheetId="' . $i . '" r:id="rIdSheet' . $i . '"/>';
            $i++;
        }
        $wb .= '</sheets>';
        $wb .= '</workbook>';
        $zip->addFromString('xl/workbook.xml', $wb);

        // 5. xl/styles.xml
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $styles .= '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
        $styles .= '<fonts count="2">';
        $styles .= '<font><sz val="11"/><name val="Calibri"/><color rgb="FF333333"/></font>'; // 0: normal
        $styles .= '<font><b/><sz val="11"/><name val="Calibri"/><color rgb="FFFFFFFF"/></font>'; // 1: header bold white
        $styles .= '</fonts>';
        $styles .= '<fills count="4">';
        $styles .= '<fill><patternFill patternType="none"/></fill>'; // 0: default
        $styles .= '<fill><patternFill patternType="gray125"/></fill>'; // 1: default
        $styles .= '<fill><patternFill patternType="solid"><fgColor rgb="FF1D4ED8"/><bgColor indexed="64"/></patternFill></fill>'; // 2: header blue
        $styles .= '<fill><patternFill patternType="solid"><fgColor rgb="FFE2E8F0"/><bgColor indexed="64"/></patternFill></fill>'; // 3: total gray
        $styles .= '</fills>';
        $styles .= '<borders count="1"><border><left/><right/><top/><bottom/></border></borders>';
        $styles .= '<cellXfs count="3">';
        $styles .= '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'; // 0: normal
        $styles .= '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"><alignment horizontal="center"/></xf>'; // 1: header
        $styles .= '<xf numFmtId="0" fontId="0" fillId="3" borderId="0" xfId="0" applyFill="1"/>'; // 2: total row
        $styles .= '</cellXfs>';
        $styles .= '</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // 6. xl/worksheets/sheetN.xml
        $sheetIdx = 1;
        foreach ($sheetsData as $sheetName => $rows) {
            // Auto-calculate column widths based on cell content lengths
            $colWidths = [];
            foreach ($rows as $row) {
                $cIdx = 1;
                foreach ($row as $val) {
                    $len = mb_strlen((string)$val, 'UTF-8');
                    if (!isset($colWidths[$cIdx]) || $len > $colWidths[$cIdx]) {
                        $colWidths[$cIdx] = $len;
                    }
                    $cIdx++;
                }
            }

            $colsXml = '<cols>';
            foreach ($colWidths as $cIdx => $maxLen) {
                // Add padding (+4) and set minimum width of 12 for clean layout
                $calculatedWidth = max($maxLen + 4, 12);
                $colsXml .= '<col min="' . $cIdx . '" max="' . $cIdx . '" width="' . $calculatedWidth . '" customWidth="1"/>';
            }
            $colsXml .= '</cols>';

            $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
            $sheetXml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
            $sheetXml .= $colsXml;
            $sheetXml .= '<sheetData>';

            $rIdx = 1;
            foreach ($rows as $row) {
                $isHeader = ($rIdx === 1);
                $isTotalRow = (isset($row[0]) && str_contains(strtoupper((string)$row[0]), 'TOTAL'));
                
                $sheetXml .= '<row r="' . $rIdx . '">';
                $cIdx = 1;
                foreach ($row as $val) {
                    $colLetter = self::getColLetter($cIdx);
                    $cellRef = $colLetter . $rIdx;

                    $styleAttr = '';
                    if ($isHeader) {
                        $styleAttr = ' s="1"';
                    } elseif ($isTotalRow) {
                        $styleAttr = ' s="2"';
                    }

                    if (is_numeric($val) && !is_string($val)) {
                        $sheetXml .= '<c r="' . $cellRef . '"' . $styleAttr . '><v>' . $val . '</v></c>';
                    } else {
                        $strVal = htmlspecialchars((string)$val, ENT_QUOTES | ENT_XML1, 'UTF-8');
                        $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr"' . $styleAttr . '><is><t>' . $strVal . '</t></is></c>';
                    }
                    $cIdx++;
                }
                $sheetXml .= '</row>';
                $rIdx++;
            }

            $sheetXml .= '</sheetData>';
            $sheetXml .= '</worksheet>';
            $zip->addFromString('xl/worksheets/sheet' . $sheetIdx . '.xml', $sheetXml);
            $sheetIdx++;
        }

        $zip->close();

        $content = file_get_contents($tempFile);
        @unlink($tempFile);

        return $content;
    }

    private static function getColLetter(int $col): string
    {
        $letter = '';
        while ($col > 0) {
            $m = ($col - 1) % 26;
            $letter = chr(65 + $m) . $letter;
            $col = (int)(($col - $m) / 26);
        }
        return $letter;
    }
}
