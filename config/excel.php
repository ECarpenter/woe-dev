<?php

return array(

    'cache'      => array(

        /*
        |--------------------------------------------------------------------------
        | Enable/Disable cell caching
        |--------------------------------------------------------------------------
        */
        'enable'   => true,

        /*
        |--------------------------------------------------------------------------
        | Caching driver
        |--------------------------------------------------------------------------
        |
        | Set the caching driver
        |
        | Available methods:
        | memory|gzip|serialized|igbinary|discISAM|apc|memcache|temp|wincache|sqlite|sqlite3
        |
        */
        'driver'   => 'memory',

        /*
        |--------------------------------------------------------------------------
        | Cache settings
        |--------------------------------------------------------------------------
        */
        'settings' => array(

            'memoryCacheSize' => '32MB',
            'cacheTime'       => 600

        ),

        /*
        |--------------------------------------------------------------------------
        | Memcache settings
        |--------------------------------------------------------------------------
        */
        'memcache' => array(

            'host' => 'localhost',
            'port' => 11211,

        ),

        /*
        |--------------------------------------------------------------------------
        | Cache dir (for discISAM)
        |--------------------------------------------------------------------------
        */

        'dir'      => storage_path('cache')
    ),

    'properties' => array(
        'creator'        => 'Maatwebsite',
        'lastModifiedBy' => 'Maatwebsite',
        'title'          => 'Spreadsheet',
        'description'    => 'Default spreadsheet export',
        'subject'        => 'Spreadsheet export',
        'keywords'       => 'maatwebsite, excel, export',
        'category'       => 'Excel',
        'manager'        => 'Maatwebsite',
        'company'        => 'Maatwebsite',
    ),

    /*
    |--------------------------------------------------------------------------
    | Sheets settings
    |--------------------------------------------------------------------------
    */
    'sheets'     => array(

        /*
        |--------------------------------------------------------------------------
        | Default page setup
        |--------------------------------------------------------------------------
        */
        'pageSetup' => array(
            'orientation'           => 'portrait',
            'paperSize'             => '9',
            'scale'                 => '100',
            'fitToPage'             => false,
            'fitToHeight'           => true,
            'fitToWidth'            => true,
            'columnsToRepeatAtLeft' => array('', ''),
            'rowsToRepeatAtTop'     => array(0, 0),
            'horizontalCentered'    => false,
            'verticalCentered'      => false,
            'printArea'             => null,
            'firstPageNumber'       => null,
        ),
    ),

    /*
    |--------------------------------------------------------------------------
    | Creator
    |--------------------------------------------------------------------------
    |
    | The default creator of a new Excel file
    |
    */

    'creator'    => 'Maatwebsite',

    'csv'        => array(
        /*
       |--------------------------------------------------------------------------
       | Delimiter
       |--------------------------------------------------------------------------
       |
       | The default delimiter which will be used to read out a CSV file
       |
       */

        'delimiter'   => ',',

        /*
        |--------------------------------------------------------------------------
        | Enclosure
        |--------------------------------------------------------------------------
        */

        'enclosure'   => '"',

        /*
        |--------------------------------------------------------------------------
        | Line endings
        |--------------------------------------------------------------------------
        */

        'line_ending' => "\r\n"
    ),

    'export'     => array(

        /*
        |--------------------------------------------------------------------------
        | Autosize columns
        |--------------------------------------------------------------------------
        |
        | Disable/enable column autosize or set the autosizing for
        | an array of columns ( array('A', 'B') )
        |
        */
        'autosize'                    => true,

        /*
        |--------------------------------------------------------------------------
        | Autosize method
        |--------------------------------------------------------------------------
        |
        | --> PHPExcel_Shared_Font::AUTOSIZE_METHOD_APPROX
        | The default is based on an estimate, which does its calculation based
        | on the number of characters in the cell value (applying any calculation
        | and format mask, and allowing for wordwrap and rotation) and with an
        | "arbitrary" adjustment based on the font (Arial, Calibri or Verdana,
        | defaulting to Calibri if any other font is used) and a proportional
        | adjustment for the font size.
        |
        | --> PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT
        | The second method is more accurate, based on actual style formatting as
        | well (bold, italic, etc), and is calculated by generating a gd2 imagettf
        | bounding box and using its dimensions to determine the size; but this
        | method is significantly slower, and its accuracy is still dependent on
        | having the appropriate fonts installed.
        |
        */
        'autosize-method'             => PHPExcel_Shared_Font::AUTOSIZE_METHOD_APPROX,

        /*
        |--------------------------------------------------------------------------
        | Auto generate table heading
        |--------------------------------------------------------------------------
        |
        | If set to true, the array indices (or model attribute names)
        | will automatically be used as first row (table heading)
        |
        */
        'generate_heading_by_indices' => true,

        /*
        |--------------------------------------------------------------------------
        | Auto set alignment on merged cells
        |--------------------------------------------------------------------------
        */
        'merged_cell_alignment'       => 'left',

        /*
        |--------------------------------------------------------------------------
        | Pre-calculate formulas during export
        |--------------------------------------------------------------------------
        */
        'calculate'                   => false,

        /*
        |--------------------------------------------------------------------------
        | Include Charts during export
        |--------------------------------------------------------------------------
        */
        'includeCharts'               => false,

        /*
        |--------------------------------------------------------------------------
        | Default sheet settings
        |--------------------------------------------------------------------------
        */
        'sheets'                      => array(

            /*
            |--------------------------------------------------------------------------
            | Default page margin
            |--------------------------------------------------------------------------
            |
            | 1) When set to false, default margins will be used
            | 2) It's possible to enter a single margin which will
            |    be used for all margins.
            | 3) Alternatively you can pass an array with 4 margins
            |    Default order: array(top, right, bottom, left)
            |
            */
            'page_margin'          => false,

            /*
            |--------------------------------------------------------------------------
            | Value in source array that stands for blank cell
            |--------------------------------------------------------------------------
            */
            'nullValue'            => null,

            /*
            |--------------------------------------------------------------------------
            | Insert array starting from this cell address as the top left coordinate
            |--------------------------------------------------------------------------
            */
            'startCell'            => 'A1',

            /*
            |--------------------------------------------------------------------------
            | Apply strict comparison when testing for null values in the array
            |--------------------------------------------------------------------------
            */
            'strictNullComparison' => false
        ),

        /*
        |--------------------------------------------------------------------------
        | Store settings
        |--------------------------------------------------------------------------
        */

        'store'                       => array(

            /*
            |--------------------------------------------------------------------------
            | Path
            |--------------------------------------------------------------------------
            |
            | The path we want to save excel file to
            |
            */
            'path'       => storage_path('exports'),

            /*
            |--------------------------------------------------------------------------
            | Return info
            |--------------------------------------------------------------------------
            |
            | Whether we want to return information about the stored file or not
            |
            */
            'returnInfo' => false

        ),

        /*
        |--------------------------------------------------------------------------
        | PDF Settings
        |--------------------------------------------------------------------------
        */
        'pdf'                         => array(

            /*
            |--------------------------------------------------------------------------
            | PDF Drivers
            |--------------------------------------------------------------------------
            | Supported: DomPDF, tcPDF, mPDF
            */
            'driver'  => 'tcPDF',

            /*
            |--------------------------------------------------------------------------
            | PDF Driver settings
            |--------------------------------------------------------------------------
            */
            'drivers' => array(

                /*
                |--------------------------------------------------------------------------
                | DomPDF settings
                |--------------------------------------------------------------------------
                */
                'DomPDF' => array(
                    'path' => base_path('vendor/dompdf/dompdf/')
                ),

                /*
                |--------------------------------------------------------------------------
                | tcPDF settings
                |--------------------------------------------------------------------------
                */
                'tcPDF'  => array(
                    'path' => base_path('vendor/tecnickcom/tcpdf/')
                ),

                /*
                |--------------------------------------------------------------------------
                | mPDF settings
                |--------------------------------------------------------------------------
                */
                'mPDF'   => array(
                    'path' => base_path('vendor/mpdf/mpdf/')
                ),
            )
        )
    ),

    'filters'    => array(
        /*
        |--------------------------------------------------------------------------
        | Register read filters
        |--------------------------------------------------------------------------
        */

        'registered' => array(
            'chunk' => 'Maatwebsite\Excel\Filters\ChunkReadFilter'
        ),

        /*
        |--------------------------------------------------------------------------
        | Enable certain filters for every file read
        |--------------------------------------------------------------------------
        */

        'enabled'    => array()
    ),

    'import'     => array(

        /*
        |--------------------------------------------------------------------------
        | Has heading
        |--------------------------------------------------------------------------
        |
        | The sheet has a heading (first) row which we can use as attribute names
        |
        | Options: true|false|slugged|slugged_with_count|ascii|numeric|hashed|trans|original
        |
        */

        'heading'                 => 'slugged',

        /*
        |--------------------------------------------------------------------------
        | First Row with data or heading of data
        |--------------------------------------------------------------------------
        |
        | If the heading row is not the first row, or the data doesn't start
        | on the first row, here you can change the start row.
        |
        */

        'startRow'                => 1,

        /*
        |--------------------------------------------------------------------------
        | Cell name word separator
        |--------------------------------------------------------------------------
        |
        | The default separator which is used for the cell names
        | Note: only applies to 'heading' settings 'true' && 'slugged'
        |
        */

        'separator'               => '_',

        /*
        |--------------------------------------------------------------------------
        | Include Charts during import
        |--------------------------------------------------------------------------
        */

        'includeCharts'           => false,

        /*
        |--------------------------------------------------------------------------
        | Sheet heading conversion
        |--------------------------------------------------------------------------
        |
        | Convert headings to ASCII
        | Note: only applies to 'heading' settings 'true' && 'slugged'
        |
        */

        'to_ascii'                => true,

        /*
        |--------------------------------------------------------------------------
        | Import encoding
        |--------------------------------------------------------------------------
        */

        'encoding'                => array(

            'input'  => 'UTF-8',
            'output' => 'UTF-8'

        ),

        /*
        |--------------------------------------------------------------------------
        | Calculate
        |--------------------------------------------------------------------------
        |
        | By default cells with formulas will be calculated.
        |
        */

        'calculate'               => true,

        /*
        |--------------------------------------------------------------------------
        | Ignore empty cells
        |--------------------------------------------------------------------------
        |
        | By default empty cells are not ignored
        |
        */

        'ignoreEmpty'             => false,

        /*
        |--------------------------------------------------------------------------
        | Force sheet collection
        |--------------------------------------------------------------------------
        |
        | For a sheet collection even when there is only 1 sheets.
        | When set to false and only 1 sheet found, the parsed file will return
        | a row collection instead of a sheet collection.
        | When set to true, it will return a sheet collection instead.
        |
        */
        'force_sheets_collection' => true,

        /*
        |--------------------------------------------------------------------------
        | Date format
        |--------------------------------------------------------------------------
        |
        | The format dates will be parsed to
        |
        */

        'dates'                   => array(

            /*
            |--------------------------------------------------------------------------
            | Enable/disable date formatting
            |--------------------------------------------------------------------------
            */
            'enabled' => true,

            /*
            |--------------------------------------------------------------------------
            | Default date format
            |--------------------------------------------------------------------------
            |
            | If set to false, a carbon object will return
            |
            */
            'format'  => false,

            /*
            |--------------------------------------------------------------------------
            | Date columns
            |--------------------------------------------------------------------------
            */
            'columns' => array()
        ),

        /*
        |--------------------------------------------------------------------------
        | Import sheets by config
        |--------------------------------------------------------------------------
        */
        'sheets'                  => array(

            /*
            |--------------------------------------------------------------------------
            | Example sheet
            |--------------------------------------------------------------------------
            |
            | Example sheet "test" will grab the firstname at cell A2
            |
            */
           //Insurance requirement form, the form specifies a lease to pull the standard requirements from in the general information
           'general-ins-req' => array(
                'Property-Name' => 'C6',
                'Property-ID' => 'C7',
                'Lease-to-Use' => 'C8',
                'Additional-Insured' => 'C9'
           ),
           'lease_1' => array(
                'CGL' => 'B16',
                'CGL-Other' => 'D16',
                'CGL-Deductible' => 'F16',
                'CGL-Deductible-Other' => 'H16',
                'Excess' => 'B18', 
                'Excess-Coverage' => 'D18',
                'Excess-Coverage-Other' => 'F18',
                'Umbrella' => 'B19',
                'Umbrella-Coverage' => 'D19',
                'Umbrella-Coverage-Other' => 'F19',
                'Pollution-Exclusion' => 'C22',
                'Additional-Insured-Managers' => 'D23',
                'Cause-of-Loss' => 'B25',
                'TPP' => 'D25',
                'TI' => 'F25',
                'Tenants-fixtures' => 'H25',
                'Cause-of-Loss-Detail' => 'F26',
                'Data-Endorsement' => 'H27',
                'Earthquake' => 'B29',
                'Flood' => 'B31',
                'Pollution-Liability' => 'B33',
                'Pollution-Liability-Other' => 'D33',
                'Workers-Comp' => 'B35',
                'Employers-Liability' => 'B37',
                'Employers-Liability-Other' => 'D37',
                'Auto-Liability' => 'B39',
                'Auto-Liability-Coverage' => 'D39',
                'Auto-Liability-Coverage-Other' => 'F39',
                'Business-Interruption' => 'B41',
                'Waiver-of-Subrogation' => 'B43'
            ),

            'lease_2' => array(
                'CGL' => 'B50',
                'CGL-Other' => 'D50',
                'CGL-Deductible' => 'F50',
                'CGL-Deductible-Other' => 'H50',
                'Excess' => 'B52', 
                'Excess-Coverage' => 'D52',
                'Excess-Coverage-Other' => 'F52',
                'Umbrella' => 'B53',
                'Umbrella-Coverage' => 'D53',
                'Umbrella-Coverage-Other' => 'F53',
                'Pollution-Exclusion' => 'C56',
                'Additional-Insured-Managers' => 'D57',
                'Cause-of-Loss' => 'B59',
                'TPP' => 'D59',
                'TI' => 'F59',
                'Tenants-fixtures' => 'H59',
                'Cause-of-Loss-Detail' => 'F60',
                'Data-Endorsement' => 'H61',
                'Earthquake' => 'B63',
                'Flood' => 'B65',
                'Pollution-Liability' => 'B67',
                'Pollution-Liability-Other' => 'D67',
                'Workers-Comp' => 'B69',
                'Employers-Liability' => 'B71',
                'Employers-Liability-Other' => 'D71',
                'Auto-Liability' => 'B73',
                'Auto-Liability-Coverage' => 'D73',
                'Auto-Liability-Coverage-Other' => 'F73',
                'Business-Interruption' => 'B75',
                'Waiver-of-Subrogation' => 'B77'
            ),

            'lease_summary' => array(
                'Summary-Type' => 'G6',
                'Property-ID' =>'C6',
                'Tenant-ID' => 'C13',
                'Tenant-Name' => 'C11',
                'Suite' => 'C8',
                'E-Mail' => 'C21',
                'Lease-Expiration' => 'C30',
                
            ),
            'lease_normal' => array(
                'Desired-Version' => '4.21.18',
                'Version' => 'B159',
                'CGL' => 'B130',
                'CGL-Other' => 'D130',
                'CGL-Deductible' => 'F130',
                'CGL-Deductible-Other' => 'H130',
                'Excess' => 'B132', 
                'Excess-Coverage' => 'D132',
                'Excess-Coverage-Other' => 'F132',
                'Umbrella' => 'B133',
                'Umbrella-Coverage' => 'D133',
                'Umbrella-Coverage-Other' => 'F133',
                'Pollution-Exclusion' => 'C136',
                'Additional-Insured-Managers' => 'D137',
                'Cause-of-Loss' => 'B139',
                'TPP' => 'D139',
                'TI' => 'F139',
                'Tenants-fixtures' => 'H139',
                'Cause-of-Loss-Detail' => 'F140',
                'Data-Endorsement' => 'H141',
                'Earthquake' => 'B143',
                'Flood' => 'B145',
                'Pollution-Liability' => 'B147',
                'Pollution-Liability-Other' => 'D147',
                'Workers-Comp' => 'B149',
                'Employers-Liability' => 'B151',
                'Employers-Liability-Other' => 'D151',
                'Auto-Liability' => 'B153',
                'Auto-Liability-Coverage' => 'D153',
                'Auto-Liability-Coverage-Other' => 'F153',
                'Business-Interruption' => 'B155',
                'Waiver-of-Subrogation' => 'B157'
            ),
            'lease_renewal' => array(
                'Desired-Version' => '4.17.18',
                'Version' => 'B114',
                'CGL' => 'B85',
                'CGL-Other' => 'D85',
                'CGL-Deductible' => 'F85',
                'CGL-Deductible-Other' => 'H85',
                'Excess' => 'B87', 
                'Excess-Coverage' => 'D87',
                'Excess-Coverage-Other' => 'F87',
                'Umbrella' => 'B88',
                'Umbrella-Coverage' => 'D88',
                'Umbrella-Coverage-Other' => 'F88',
                'Pollution-Exclusion' => 'C91',
                'Additional-Insured-Managers' => 'D92',
                'Cause-of-Loss' => 'B94',
                'TPP' => 'D94',
                'TI' => 'F94',
                'Tenants-fixtures' => 'H94',
                'Cause-of-Loss-Detail' => 'F95',
                'Data-Endorsement' => 'H96',
                'Earthquake' => 'B98',
                'Flood' => 'B100',
                'Pollution-Liability' => 'B102',
                'Pollution-Liability-Other' => 'D102',
                'Workers-Comp' => 'B104',
                'Employers-Liability' => 'B106',
                'Employers-Liability-Other' => 'D106',
                'Auto-Liability' => 'B108',
                'Auto-Liability-Coverage' => 'D108',
                'Auto-Liability-Coverage-Other' => 'F108',
                'Business-Interruption' => 'B110',
                'Waiver-of-Subrogation' => 'B12'
            )

        )
    ),

    'views'      => array(

        /*
        |--------------------------------------------------------------------------
        | Styles
        |--------------------------------------------------------------------------
        |
        | The default styles which will be used when parsing a view
        |
        */

        'styles' => array(

            /*
            |--------------------------------------------------------------------------
            | Table headings
            |--------------------------------------------------------------------------
            */
            'th'     => array(
                'font' => array(
                    'bold' => true,
                    'size' => 12,
                )
            ),

            /*
            |--------------------------------------------------------------------------
            | Strong tags
            |--------------------------------------------------------------------------
            */
            'strong' => array(
                'font' => array(
                    'bold' => true,
                    'size' => 12,
                )
            ),

            /*
            |--------------------------------------------------------------------------
            | Bold tags
            |--------------------------------------------------------------------------
            */
            'b'      => array(
                'font' => array(
                    'bold' => true,
                    'size' => 12,
                )
            ),

            /*
            |--------------------------------------------------------------------------
            | Italic tags
            |--------------------------------------------------------------------------
            */
            'i'      => array(
                'font' => array(
                    'italic' => true,
                    'size'   => 12,
                )
            ),

            /*
            |--------------------------------------------------------------------------
            | Heading 1
            |--------------------------------------------------------------------------
            */
            'h1'     => array(
                'font' => array(
                    'bold' => true,
                    'size' => 24,
                )
            ),

            /*
            |--------------------------------------------------------------------------
            | Heading 2
            |--------------------------------------------------------------------------
            */
            'h2'     => array(
                'font' => array(
                    'bold' => true,
                    'size' => 18,
                )
            ),

            /*
            |--------------------------------------------------------------------------
            | Heading 2
            |--------------------------------------------------------------------------
            */
            'h3'     => array(
                'font' => array(
                    'bold' => true,
                    'size' => 13.5,
                )
            ),

            /*
             |--------------------------------------------------------------------------
             | Heading 4
             |--------------------------------------------------------------------------
             */
            'h4'     => array(
                'font' => array(
                    'bold' => true,
                    'size' => 12,
                )
            ),

            /*
             |--------------------------------------------------------------------------
             | Heading 5
             |--------------------------------------------------------------------------
             */
            'h5'     => array(
                'font' => array(
                    'bold' => true,
                    'size' => 10,
                )
            ),

            /*
             |--------------------------------------------------------------------------
             | Heading 6
             |--------------------------------------------------------------------------
             */
            'h6'     => array(
                'font' => array(
                    'bold' => true,
                    'size' => 7.5,
                )
            ),

            /*
             |--------------------------------------------------------------------------
             | Hyperlinks
             |--------------------------------------------------------------------------
             */
            'a'      => array(
                'font' => array(
                    'underline' => true,
                    'color'     => array('argb' => 'FF0000FF'),
                )
            ),

            /*
             |--------------------------------------------------------------------------
             | Horizontal rules
             |--------------------------------------------------------------------------
             */
            'hr'     => array(
                'borders' => array(
                    'bottom' => array(
                        'style' => 'thin',
                        'color' => array('FF000000')
                    ),
                )
            )
        )

    )

);
