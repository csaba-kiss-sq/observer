<section class="title">
	<h4><?php echo lang('observer:charts_title') ?></h4>
</section>

<section class="item">
	<div class="content">


		<fieldset id="filters">
 
    <legend><?php echo lang('global:filters'); ?></legend>
 
    <?php echo form_open(); ?>
 
    <?php echo form_hidden('f_module', $module_details['slug']); ?>
        <ul> 
            <li id ="dropdown_type">
                <?php echo form_dropdown('f_status', array(0 => 'Termékek', 1 => 'Kereskedők' )); ?>
            </li>
            <li>
                <?php echo form_dropdown('f_status', $categories ); ?>
            </li>

        </ul>
    <?php echo form_close(); ?>
</fieldset>
	<!-- {{ url:base }} -->
		<dl>
			<!-- <dt>Kereskedő neve</dt> -->
			<!-- <dl>Kereskedő 1</dl> -->
			<dt>Árfolyam</dt>
			<dl>
				<div id="lineChart" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
				<div class="diagram"></div>
				<a href="" class="diagramReload">Frissítés</a>
			</dl>

		</dl>
	</div>
</section>
	

<script type="text/javascript">
var baseUrl =  "{{ url:base }}";

$(document).ready(function() 
{
	var chart,
		chartOptions;

	var chartOptions = {
        chart: {
            renderTo: 'lineChart',
            type: 'line',
            marginRight: 130,
            marginBottom: 25
        },
        title: {
            text: 'Árfolyamok alakulása termékeknként',
            x: -20 //center
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
            title: {
                text: 'Ár'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function() {
                    return '<b>'+ this.series.name +'</b><br/>'+
                    this.x +': '+ this.y +' Ft.';
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -10,
            y: 100,
            borderWidth: 0
        },
    };

    var series = Array();
    eval("<?php echo $json; ?>");

    chartOptions.series = series;
    chart = new Highcharts.Chart(chartOptions);
});
</script>
