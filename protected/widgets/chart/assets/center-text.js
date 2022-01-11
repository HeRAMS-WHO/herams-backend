"use strict";

Chart.pluginService.register({
    beforeDraw: function (chart) {
        if (chart.config.options.elements.center) {
            //Get ctx from string
            var ctx = chart.chart.ctx;

            //Get options from the center object in options
            var centerConfig = chart.config.options.elements.center;
            var fontStyle = centerConfig.fontStyle || 'Arial';
            let fontWeight = centerConfig.fontWeight || 'regular';
            let txt = centerConfig.text;
            if (txt instanceof  Function) {
                txt = txt.call(null, chart);
            }


            let color = centerConfig.color || '#000';
            let sidePadding = centerConfig.sidePadding || 20;
            let sidePaddingCalculated = (sidePadding/100) * (chart.innerRadius * 2);
            //Start with a base font of 30px
            ctx.font = 'normal normal ' + fontWeight + ' 30px "' + fontStyle + '"';

            //Get the width of the string and also the width of the element minus 10 to give it 5px side padding
            let stringWidth = ctx.measureText(txt).width;

            let elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

            // Find out how much the font can grow in width.
            var widthRatio = elementWidth / stringWidth;
            var newFontSize = Math.floor(30 * widthRatio);
            var elementHeight = (chart.innerRadius * 2);


            // Pick a new font size so it will not be larger than the height of label.
            var fontSizeToUse = Math.min(newFontSize, elementHeight) * 0.9;

            //Set font settings to draw it correctly.
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
            var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
            ctx.font = 'normal normal ' + fontWeight + ' ' + fontSizeToUse + 'px "' + fontStyle + '"';
            ctx.fillStyle = color;

            //Draw text in center
            ctx.fillText(txt, centerX, centerY);
        }

        if (chart.config.options.elements.topRight) {
            //Get ctx from string
            let ctx = chart.chart.ctx;

            //Get options from the center object in options
            let config = chart.config.options.elements.topRight;
            let fontStyle = config.fontStyle || 'Arial';
            let fontWeight = config.fontWeight || 'regular';
            let txt = config.text;
            if (txt instanceof Function) {
                txt = txt.call(null, chart);
            }

            let color = config.color || '#000';
            let sidePadding = config.sidePadding || 20;
            let sidePaddingCalculated = (sidePadding/100) * (chart.config * 2);
            //Start with a base font of 30px
            ctx.font = 'normal normal ' + fontWeight + ' 30px "' + fontStyle + '"';

            //Get the width of the string and also the width of the element minus 10 to give it 5px side padding
            let stringWidth = ctx.measureText(txt).width;

            let elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

            // Find out how much the font can grow in width.
            var widthRatio = elementWidth / stringWidth;
            var newFontSize = Math.floor(30 * widthRatio);
            var elementHeight = (chart.innerRadius * 2);


            // Pick a new font size so it will not be larger than the height of label.
            var fontSizeToUse = Math.min(newFontSize, elementHeight) * 0.9;

            //Set font settings to draw it correctly.
            ctx.textAlign = 'right';
            ctx.textBaseline = 'top';
            let x = chart.chartArea.right - 10;
            let y = chart.chartArea.top + 10;
            ctx.font = 'normal normal ' + fontWeight + ' ' + fontSizeToUse + 'px "' + fontStyle + '"';
            ctx.fillStyle = color;

            //Draw text in center
            ctx.fillText(txt, x, y);
        }
    }
});
