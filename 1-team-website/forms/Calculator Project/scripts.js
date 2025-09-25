function calculateVelocity() {
    
    var displacement = $('#displacement').val();
    var time = $('#time').val();


    var velocity = displacement / time;

    $('#velocity').html('Velocity is: ' + velocity);

}

function calculateSurfaceArea() {

    var baselength = $('baseLength').val();
    var basewidth = $('baseWidth').val();
}