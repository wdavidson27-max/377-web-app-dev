function calculateVelocity() {
    
    var displacement = $('#displacement').val();
    var time = $('#time').val();


    var velocity = displacement / time;

    $('#velocity').html('Your speed is: ' + velocity + 'meters per second');

}

function calculateSurfaceArea() {

    var baseLength = $('#baseLength').val();
    var baseWidth = $('#baseWidth').val();
    var baseHeight = $('#baseHeight').val();

    var baseArea = baseLength * baseWidth;

    var perimBase = (2 * baseLength) + (2 * baseWidth);

    var totalArea = baseArea + (perimBase / 2) * baseHeight;

    $('#totalArea').html('Total Surface Area is: ' + totalArea + " Square Units")
}

function CalculateSlope() {

    var x1 = $('#x1').val();
    var y1 = $('#y1').val();
    var x2 = $('#x2').val();
    var y2 = $('#y2').val();

    var vertDist = y2 - y1;

    var horizDist = x2 - x1;

    var slope = vertDist / horizDist;

    $('#slope').html('The Slope of your Line is: ' + slope);
}