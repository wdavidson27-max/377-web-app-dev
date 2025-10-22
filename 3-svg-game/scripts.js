var turn = false;



function dropChip(column) {
    for (let row = 5; row > -1; row --) {
        
        console.log(row);
        var currentSpot = $('#c' + column + 'r' + row).attr("fill");
        console.log(currentSpot);
        if (currentSpot == 'white') {
            if (turn) {
                $('#c' + column + 'r' + row).attr("fill", "red");
                checkForWinner("red");
                turn = false;
                break;
            } else {
                $('#c' + column + 'r' + row).attr("fill", "yellow");
                checkForWinner("yellow");
                turn = true;
                break;
            }
            
            
        }

    }

}

function checkForWinnerInColumn(color, column) {
    if ($('#c' + column + 'r5').attr("fill") == color && 
        $('#c' + column + 'r4').attr("fill") == color &&
        $('#c' + column + 'r3').attr("fill") == color &&  
        $('#c' + column + 'r2').attr("fill") == color) {
        return true;
        
    }
    if ($('#c' + column + 'r4').attr("fill") == color && 
        $('#c' + column + 'r3').attr("fill") == color &&
        $('#c' + column + 'r2').attr("fill") == color &&  
        $('#c' + column + 'r1').attr("fill") == color) {
        return true;
        
    }
    if ($('#c' + column + 'r3').attr("fill") == color && 
        $('#c' + column + 'r2').attr("fill") == color &&
        $('#c' + column + 'r1').attr("fill") == color &&  
        $('#c' + column + 'r0').attr("fill") == color) {
        return true;
        
    }
    return false;

}
function checkForWinnerinRow(color, row) {
    if ($('#c6r' + row).attr("fill") == color && 
        $('#c5r' + row).attr("fill") == color &&
        $('#c4r' + row).attr("fill") == color &&  
        $('#c3r' + row).attr("fill") == color) {
        return true;
        
    }
    return false;
}

function checkForWinner(color) {
    for (let column = 0; column < 7; column++) {
        if (checkForWinnerInColumn(color, column)) {
            console.log("You won in col");
            break;
        }
    }
    for (let row = 0; row < 6; row++) {
        if (checkForWinnerinRow(color, row)) {
            console.log("You won in row");
            break;
        }
    }
}



