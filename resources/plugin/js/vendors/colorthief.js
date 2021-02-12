/**
 * Load required plugin.
 */
window.ColorThief = require('colorthief/dist/color-thief.umd');

/**
 * Configure the plugin.
 */

+function ($) {

    page.registerVendor('ColorThief');

    page.initColorThief = function () {

        const colorThief = new ColorThief();

        // Convert RGB to hexadecimal
        const rgbToHex = (r, g, b) => '#' + [r, g, b].map(x => {
            const hex = x.toString(16)
            return hex.length === 1 ? '0' + hex : hex
        }).join('')

        $('[data-provide~="colorthief"]').each(function (index) {

            var img = this;

            if (img.complete) {

                // Get hex color for dominant
                var rgbDominant = colorThief.getColor(img);
                var hexDominant = rgbToHex(rgbDominant[0], rgbDominant[1], rgbDominant[2])

                // Get hex color for palette
                var rgbPalette = colorThief.getPalette(img);
                var hexPalette = [];

                rgbPalette.forEach(item => {
                    hexPalette.push(rgbToHex(item[0], item[1], item[2]));
                });

                // Update values
                $(this).data('dominant', hexDominant);
                $(this).data('palette', hexPalette);

                // console.log(index + ": " + colorThief.getColor(img));
                // console.log(index + ": " + colorThief.getPalette(img));

            } else {
                image.addEventListener('load', function () {
                    colorThief.getColor(img);
                    colorThief.getPalette(img);
                });
            }
        });
    }
}(jQuery);
