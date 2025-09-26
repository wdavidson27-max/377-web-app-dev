function calculateVelocity() {
    
    var displacement = $('#displacement').val();
    var time = $('#time').val();


    var velocity = displacement / time;

    $('#velocity').html('Velocity is: ' + velocity);

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