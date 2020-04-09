window.am4core = require('@amcharts/amcharts4/core');
window.am4charts = require('@amcharts/amcharts4/charts');
window.am4plugins_regression = require('@amcharts/amcharts4/plugins/regression');

import am4themes_animated from "@amcharts/amcharts4/themes/animated";
window.am4core.useTheme(am4themes_animated);
// import am4themes_dataviz from "@amcharts/amcharts4/themes/dataviz";
// import am4themes_spiritedaway from "@amcharts/amcharts4/themes/spiritedaway";
// import am4themes_material from "@amcharts/amcharts4/themes/material";
// import am4themes_kelly from "@amcharts/amcharts4/themes/kelly";
import am4themes_amcharts from "@amcharts/amcharts4/themes/amcharts";
window.am4core.useTheme(am4themes_amcharts);
