/**
 * Load required plugin.
 */

// Reference:
// https://datatables.net/download/


// require('pdfmake'); // Export to PDF
// require('datatables.net'); // Core
// require('datatables.net-bs4'); // Bootstrasp 4 Core
// require('datatables.net-buttons'); // Buttons extension
require('datatables.net-buttons-bs4')(); // Buttons extension for Bootstrap 4
require('datatables.net-buttons/js/buttons.colVis.js')(); // Column visibility
require('datatables.net-buttons/js/buttons.html5.js')();  // HTML 5 file export
// require('datatables.net-buttons/js/buttons.flash.js')();
require('datatables.net-buttons/js/buttons.print.js')();  // Print view button
// require('datatables.net-responsive-bs4')();


/**
 * Configure the plugin.
 */

+ function ($) {
    page.registerVendor('Datatables');

    page.initDatatables = function () {

        $('[data-provide~="datatables-full"]').each(function () {

            // Custom with some options
            var table = $(this).DataTable({
                dom: 'Bfrltip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: 'Show Columns'
                    },
                    'copy', 'csv', 'print'
                ],
                columnDefs: [
                    {
                        "targets": [0, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 21],
                        "visible": false
                    }
                ],
                lengthMenu: [[10, 25, 50, 100, 250, 500, -1], [10, 25, 50, 100, 250, 500, "All"]]
            });
        });

        $('[data-provide~="datatables"]').each(function () {

            // Custom with some options
            var table = $(this).DataTable({
                dom: 'frltip',
                lengthMenu: [[10, 25, 50, 100, 250, 500, -1], [10, 25, 50, 100, 250, 500, "All"]],
                fnInfoCallback: function (oSettings) {
                    if (typeof loadSwitch === "function") {
                        loadSwitch();
                    }
                }
            });
        });
    }
}(jQuery);
