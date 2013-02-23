<section class="title">
	<h4><?php echo lang('observer:charts_title') ?></h4>
</section>

<section class="item">

	<div class="content">
        <div id="grafikon_target"></div>
    </div>

</section>
	
<script type="text/javascript">

var seriesOptions = [],
    <? if(!empty($products)): ?>
    way = "by_products",
    elements = <?= json_encode($products) ?>,
    <? elseif(!empty($merchants)): ?>
    way = "by_products",
    elements = <?= json_encode($merchants) ?>,
    <? endif; ?>
    categories_id = <?= $params['categories_id']; ?>,

    seriesCounter = 0,
    API_URL = SITE_URL + "admin/observer/charts/get_charts_data/";

function createChart() {

    chart = new Highcharts.StockChart({

        chart : {
            renderTo : "grafikon_target"
        },

         rangeSelector : {
            buttons : [{
                type : 'hour',
                count : 12,
                text : '12ó'
            }, {
                type : 'day',
                count : 7,
                text : '1hét'
            }, {
                type : 'month',
                count : 1,
                text : '1hó'
            }, {
                type : 'month',
                count : 3,
                text : '3hó'
            }, {
                type : 'year',
                count : 1,
                text : '1év'
            }],
            selected : 1,
            //enabled:false,
            inputEnabled : false
        },

        yAxis: {
            labels: {
                formatter: function() {
                    return (this.value > 0 ? '+' : '') + this.value;
                }
            },
            plotLines: [{
                value: 0,
                width: 2,
                color: 'silver'
            }]
        },
        
        plotOptions: {
            series: {
             //   compare: 'percent'
            }
        }, 
        
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
            valueDecimals: 2
        },
        
        series: seriesOptions
    });
}

$( document ).ready( function() {

    $.each( elements, function( i, name ) {

        if( way == 'by_products' ) {
            products_id = name;
            merchants_id = "<?= $params['constant_id']; ?>";
        } else {
            products_id = "<?= $params['constant_id']; ?>";
            merchants_id = name;
        } 

        $.getJSON( API_URL + '/' + products_id + '/' + merchants_id + '/' + categories_id, function( data ) {

            seriesOptions[i] = {
                name : name,
                data : data
            };

            seriesCounter++;

            if ( seriesCounter == elements.length ) {
                createChart();
            }
        });
    });
});

</script>
