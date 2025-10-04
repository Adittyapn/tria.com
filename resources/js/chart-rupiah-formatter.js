// Format Rupiah for Chart.js
document.addEventListener("DOMContentLoaded", function () {
    // Wait for Chart.js to be loaded
    if (typeof Chart !== "undefined") {
        // Store the original Chart update method
        const originalUpdate = Chart.prototype.update;

        // Override Chart update to add Rupiah formatting
        Chart.prototype.update = function (mode) {
            // Check if this chart has revenue data (y-axis with Rupiah)
            if (
                this.config &&
                this.config.options &&
                this.config.options.scales
            ) {
                const yAxis = this.config.options.scales.y;

                // Add Rupiah formatter to Y-axis ticks
                if (
                    yAxis &&
                    yAxis.title &&
                    yAxis.title.text &&
                    yAxis.title.text.includes("Pendapatan")
                ) {
                    yAxis.ticks = yAxis.ticks || {};
                    yAxis.ticks.callback = function (value) {
                        if (value >= 1000000) {
                            return "Rp " + (value / 1000000).toFixed(1) + "jt";
                        } else if (value >= 1000) {
                            return "Rp " + (value / 1000).toFixed(0) + "rb";
                        }
                        return (
                            "Rp " + new Intl.NumberFormat("id-ID").format(value)
                        );
                    };
                }

                // Add Rupiah formatter to tooltip
                if (
                    this.config.options.plugins &&
                    this.config.options.plugins.tooltip
                ) {
                    this.config.options.plugins.tooltip.callbacks = {
                        label: function (context) {
                            let label = context.dataset.label || "";
                            if (label) {
                                label += ": ";
                            }

                            // Check if this is revenue (first dataset or contains 💰)
                            if (
                                context.datasetIndex === 0 ||
                                (context.dataset.label &&
                                    context.dataset.label.includes("💰"))
                            ) {
                                label +=
                                    "Rp " +
                                    new Intl.NumberFormat("id-ID").format(
                                        context.parsed.y,
                                    );
                            } else {
                                label += context.parsed.y;
                            }
                            return label;
                        },
                        title: function (context) {
                            return context[0].label;
                        },
                    };
                }
            }

            return originalUpdate.call(this, mode);
        };
    }
});
