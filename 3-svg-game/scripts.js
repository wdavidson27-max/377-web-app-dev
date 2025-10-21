var turn = false;



function dropChip(column) {
    for (let row = 5; row > -1; row --) {
        
        console.log(row);
        var currentSpot = $('#c' + column + 'r' + row).attr("fill");
        console.log(currentSpot);
        if (currentSpot == 'white') {
            if (turn) {
                $('#c' + column + 'r' + row).attr("fill", "red");
                turn = false;
                break;
            } else {
                $('#c' + column + 'r' + row).attr("fill", "yellow");
                turn = true;
                break;
            }
            
            
        }
    }

}

chips = []

