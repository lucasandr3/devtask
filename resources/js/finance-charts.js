import Chart from 'chart.js/auto';

function cssVar(name, fallback) {
    const v = getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    return v || fallback;
}

/** Shadcn tokens are stored as "H S% L%" — Chart.js needs a full hsl() color. */
function hslVar(name, fallback) {
    const raw = cssVar(name, '');
    if (!raw) return fallback;
    if (raw.startsWith('#') || raw.startsWith('rgb')) return raw;
    return `hsl(${raw})`;
}

function hexToRgba(hex, alpha) {
    const h = hex.replace('#', '');
    const full = h.length === 3 ? h.split('').map((c) => c + c).join('') : h;
    const n = parseInt(full, 16);
    const r = (n >> 16) & 255;
    const g = (n >> 8) & 255;
    const b = n & 255;
    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}

function makeGradient(chart, colorHex, topAlpha = 0.35, bottomAlpha = 0.02) {
    const { ctx, chartArea } = chart;
    if (!chartArea) return colorHex;
    const g = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
    g.addColorStop(0, hexToRgba(colorHex, topAlpha));
    g.addColorStop(1, hexToRgba(colorHex, bottomAlpha));
    return g;
}

function formatBRL(value) {
    return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

function suggestedMax(...series) {
    const max = Math.max(0, ...series.flat().map(Number));
    if (max === 0) return 10000;
    const exp = Math.pow(10, Math.floor(Math.log10(max)));
    return Math.ceil((max * 1.25) / exp) * exp;
}

function hasAnyValue(...series) {
    return series.flat().some((v) => Number(v) > 0);
}

function initFinanceCharts() {
    const root = document.getElementById('finance-charts-root');
    if (!root) return;

    const trend = JSON.parse(root.dataset.trend || '{}');
    const composition = JSON.parse(root.dataset.composition || '{}');
    const isDark = document.documentElement.classList.contains('dark');

    const muted = hslVar('--muted-foreground', '#94a3b8');
    const border = isDark ? 'rgba(148, 163, 184, 0.12)' : 'rgba(15, 23, 42, 0.06)';
    const card = hslVar('--card', '#ffffff');
    const emerald = '#10b981';
    const rose = '#f43f5e';
    const indigo = '#6366f1';
    const sky = '#0ea5e9';
    const amber = '#f59e0b';
    const slate = '#64748b';

    Chart.defaults.color = muted;
    Chart.defaults.borderColor = 'transparent';
    Chart.defaults.borderWidth = 0;
    Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
    Chart.defaults.font.size = 12;

    /**
     * Legenda com marcadores circulares.
     * usePointStyle desenha elipse (achatada); caixa quadrada + borderRadius = círculo.
     */
    const legendBoxSize = 10;
    const legendLabels = {
        usePointStyle: false,
        useBorderRadius: true,
        borderRadius: legendBoxSize / 2,
        boxWidth: legendBoxSize,
        boxHeight: legendBoxSize,
        padding: 14,
        font: { size: 11, weight: '500', lineHeight: 1 },
        generateLabels(chart) {
            if (chart.config.type === 'doughnut' || chart.config.type === 'pie') {
                const { labels: opts } = chart.legend.options;
                return chart.data.labels.map((label, i) => {
                    const style = chart.getDatasetMeta(0).controller.getStyle(i, false);
                    return {
                        text: label,
                        fillStyle: style.backgroundColor,
                        strokeStyle: style.backgroundColor,
                        fontColor: opts.color,
                        lineWidth: 0,
                        hidden: !chart.getDataVisibility(i),
                        index: i,
                        borderRadius: opts.borderRadius ?? legendBoxSize / 2,
                    };
                });
            }

            return Chart.defaults.plugins.legend.labels.generateLabels(chart);
        },
    };

    const scaleOpts = (maxY) => ({
        x: {
            grid: { display: false, drawBorder: false },
            border: { display: false },
            ticks: { padding: 8, font: { weight: '500' } },
        },
        y: {
            beginAtZero: true,
            suggestedMax: maxY,
            border: { display: false },
            grid: { color: border, lineWidth: 1, drawBorder: false },
            ticks: {
                padding: 10,
                maxTicksLimit: 6,
                callback: (v) => {
                    if (v >= 1000000) return 'R$ ' + (v / 1000000).toFixed(1) + 'M';
                    if (v >= 1000) return 'R$ ' + (v / 1000).toFixed(0) + 'k';
                    return 'R$ ' + v;
                },
            },
        },
    });

    const trendCanvas = document.getElementById('financeTrendChart');
    const trendEmpty = document.getElementById('financeTrendEmpty');
    const trendHasData = hasAnyValue(trend.revenue, trend.expenses, trend.balance);

    if (trendCanvas && trend.labels?.length) {
        if (!trendHasData && trendEmpty) {
            trendCanvas.classList.add('hidden');
            trendEmpty.classList.remove('hidden');
        } else {
            trendEmpty?.classList.add('hidden');
            const yMax = suggestedMax(trend.revenue, trend.expenses, trend.balance);

            new Chart(trendCanvas, {
                type: 'bar',
                data: {
                    labels: trend.labels,
                    datasets: [
                        {
                            label: 'Entradas',
                            data: trend.revenue,
                            backgroundColor: hexToRgba(emerald, 0.88),
                            hoverBackgroundColor: hexToRgba(emerald, 1),
                            borderWidth: 0,
                            borderRadius: { topLeft: 6, topRight: 6 },
                            borderSkipped: false,
                            maxBarThickness: 28,
                            pointStyle: 'circle',
                            order: 2,
                        },
                        {
                            label: 'Saídas',
                            data: trend.expenses,
                            backgroundColor: hexToRgba(rose, 0.88),
                            hoverBackgroundColor: hexToRgba(rose, 1),
                            borderWidth: 0,
                            borderRadius: { topLeft: 6, topRight: 6 },
                            borderSkipped: false,
                            maxBarThickness: 28,
                            pointStyle: 'circle',
                            order: 3,
                        },
                        {
                            type: 'line',
                            label: 'Resultado',
                            data: trend.balance,
                            borderColor: indigo,
                            backgroundColor: hexToRgba(indigo, 0.08),
                            borderWidth: 2.5,
                            pointStyle: 'circle',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: card,
                            pointBorderColor: indigo,
                            pointBorderWidth: 2,
                            pointHoverBorderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            order: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            align: 'start',
                            labels: legendLabels,
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1e293b' : '#0f172a',
                            titleFont: { size: 12, weight: '600' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: true,
                            callbacks: {
                                label: (ctx) => `${ctx.dataset.label}: ${formatBRL(ctx.parsed.y ?? ctx.parsed ?? 0)}`,
                            },
                        },
                    },
                    scales: scaleOpts(yMax),
                },
            });
        }
    }

    const compositionCanvas = document.getElementById('financeCompositionChart');
    const compositionEmpty = document.getElementById('financeCompositionEmpty');
    const compositionTotal = (composition.values || []).reduce((a, b) => a + Number(b), 0);
    const compositionHasData = compositionTotal > 0;

    if (compositionCanvas && composition.labels?.length) {
        if (!compositionHasData && compositionEmpty) {
            compositionCanvas.classList.add('hidden');
            compositionEmpty.classList.remove('hidden');
        } else {
            compositionEmpty?.classList.add('hidden');

            new Chart(compositionCanvas, {
                type: 'doughnut',
                data: {
                    labels: composition.labels,
                    datasets: [{
                        data: composition.values,
                        backgroundColor: [emerald, rose, amber, sky],
                        borderWidth: 0,
                        borderColor: 'transparent',
                        hoverBorderWidth: 0,
                        hoverOffset: 8,
                        spacing: 0,
                        pointStyle: 'circle',
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: legendLabels,
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1e293b' : '#0f172a',
                            padding: 12,
                            cornerRadius: 10,
                            callbacks: {
                                label: (ctx) => {
                                    const v = ctx.parsed ?? 0;
                                    const pct = compositionTotal > 0 ? ((v / compositionTotal) * 100).toFixed(1) : 0;
                                    return `${ctx.label}: ${formatBRL(v)} (${pct}%)`;
                                },
                            },
                        },
                    },
                },
                plugins: [{
                    id: 'centerText',
                    beforeDraw(chart) {
                        const { ctx, chartArea } = chart;
                        if (!chartArea) return;
                        const cx = (chartArea.left + chartArea.right) / 2;
                        const cy = (chartArea.top + chartArea.bottom) / 2;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillStyle = isDark ? '#f8fafc' : '#0f172a';
                        ctx.font = '600 13px ' + Chart.defaults.font.family;
                        ctx.fillText('Total', cx, cy - 14);
                        ctx.font = '700 16px ' + Chart.defaults.font.family;
                        ctx.fillText(formatBRL(compositionTotal), cx, cy + 8);
                        ctx.restore();
                    },
                }],
            });
        }
    }

    const cashCanvas = document.getElementById('financeCashChart');
    const cash = JSON.parse(root.dataset.cash || '{}');
    if (cashCanvas && cash.labels?.length && hasAnyValue(cash.in, cash.out)) {
        const yMax = suggestedMax(cash.in, cash.out);
        new Chart(cashCanvas, {
            type: 'bar',
            data: {
                labels: cash.labels,
                datasets: [
                    {
                        label: 'Entradas',
                        data: cash.in,
                        backgroundColor: hexToRgba(emerald, 0.85),
                        borderWidth: 0,
                        borderRadius: 8,
                        maxBarThickness: 48,
                        pointStyle: 'circle',
                    },
                    {
                        label: 'Saídas',
                        data: cash.out,
                        backgroundColor: hexToRgba(rose, 0.85),
                        borderWidth: 0,
                        borderRadius: 8,
                        maxBarThickness: 48,
                        pointStyle: 'circle',
                    },
                ],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: legendLabels },
                    tooltip: {
                        backgroundColor: isDark ? '#1e293b' : '#0f172a',
                        cornerRadius: 10,
                        callbacks: {
                            label: (ctx) => `${ctx.dataset.label}: ${formatBRL(ctx.parsed.x ?? 0)}`,
                        },
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        suggestedMax: yMax,
                        grid: { color: border },
                        border: { display: false },
                        ticks: {
                            callback: (v) => (v >= 1000 ? 'R$ ' + (v / 1000).toFixed(0) + 'k' : 'R$ ' + v),
                        },
                    },
                    y: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { weight: '500', size: 11 } },
                    },
                },
            },
        });
    }
}

function watchTheme() {
    const observer = new MutationObserver(() => {
        document.querySelectorAll('canvas[id^="finance"]').forEach((c) => {
            const chart = Chart.getChart(c);
            if (chart) chart.destroy();
        });
        initFinanceCharts();
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initFinanceCharts();
        watchTheme();
    });
} else {
    initFinanceCharts();
    watchTheme();
}
