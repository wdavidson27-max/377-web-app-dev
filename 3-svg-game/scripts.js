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
    if ($('#c5r' + row).attr("fill") == color && 
        $('#c4r' + row).attr("fill") == color &&
        $('#c3r' + row).attr("fill") == color &&  
        $('#c2r' + row).attr("fill") == color) {
        return true;
        
    }
    if ($('#c4r' + row).attr("fill") == color && 
        $('#c3r' + row).attr("fill") == color &&
        $('#c2r' + row).attr("fill") == color &&  
        $('#c1r' + row).attr("fill") == color) {
        return true;
        
    }
    if ($('#c3r' + row).attr("fill") == color && 
        $('#c2r' + row).attr("fill") == color &&
        $('#c1r' + row).attr("fill") == color &&  
        $('#c0r' + row).attr("fill") == color) {
        return true;
        
    }
    return false;
}

function checkForWinnerDiagonalUR(color) {

    if ($('#c0r0').attr("fill") == color && $('#c1r1').attr("fill") == color && $('#c2r2').attr("fill") == color && $('#c3r3').attr("fill") == color ||
        ($('#c0r1').attr("fill") == color && $('#c1r2').attr("fill") == color && $('#c2r3').attr("fill") == color && $('#c3r4').attr("fill") == color) ||
        ($('#c0r2').attr("fill") == color && $('#c1r3').attr("fill") == color && $('#c2r4').attr("fill") == color && $('#c3r5').attr("fill") == color) ||
        ($('#c0r0').attr("fill") == color && $('#c1r1').attr("fill") == color && $('#c2r2').attr("fill") == color && $('#c3r3').attr("fill") == color) ||
        ($('#c0r1').attr("fill") == color && $('#c1r2').attr("fill") == color && $('#c2r3').attr("fill") == color && $('#c3r4').attr("fill") == color) ||
        ($('#c0r2').attr("fill") == color && $('#c1r3').attr("fill") == color && $('#c2r4').attr("fill") == color && $('#c3r5').attr("fill") == color) ||
        ($('#c0r0').attr("fill") == color && $('#c1r1').attr("fill") == color && $('#c2r2').attr("fill") == color && $('#c3r3').attr("fill") == color) ||
        ($('#c0r1').attr("fill") == color && $('#c1r2').attr("fill") == color && $('#c2r3').attr("fill") == color && $('#c3r4').attr("fill") == color) ||
        ($('#c0r2').attr("fill") == color && $('#c1r3').attr("fill") == color && $('#c2r4').attr("fill") == color && $('#c3r5').attr("fill") == color) ||
        ($('#c0r0').attr("fill") == color && $('#c1r1').attr("fill") == color && $('#c2r2').attr("fill") == color && $('#c3r3').attr("fill") == color) ||
        ($('#c0r1').attr("fill") == color && $('#c1r2').attr("fill") == color && $('#c2r3').attr("fill") == color && $('#c3r4').attr("fill") == color) ||
        ($('#c0r2').attr("fill") == color && $('#c1r3').attr("fill") == color && $('#c2r4').attr("fill") == color && $('#c3r5').attr("fill") == color) ) {
        return true;

        }
        return false;
    }   


    //if ($('#c0r0' && '#c1r1' && '#c2r2' && '#c3r3').attr("fill") == color ||
    //    $('#c0r1' && '#c1r2' && '#c2r3' && '#c3r4').attr("fill") == color ||
    //    $('#c0r2' && '#c1r3' && '#c2r4' && '#c3r5').attr("fill") == color ||
    //    $('#c1r0' && '#c2r1' && '#c3r2' && '#c4r3').attr("fill") == color ||
    //    $('#c1r1' && '#c2r2' && '#c3r3' && '#c4r4').attr("fill") == color ||
    //    $('#c1r2' && '#c2r3' && '#c3r4' && '#c4r5').attr("fill") == color ||
    //    $('#c2r0' && '#c3r1' && '#c4r2' && '#c5r3').attr("fill") == color ||
    //    $('#c2r1' && '#c3r2' && '#c4r3' && '#c5r4').attr("fill") == color ||
    //    $('#c2r2' && '#c3r3' && '#c4r4' && '#c5r5').attr("fill") == color ||
    //    $('#c3r0' && '#c4r1' && '#c5r2' && '#c6r3').attr("fill") == color ||
    //    $('#c3r1' && '#c4r2' && '#c5r3' && '#c6r4').attr("fill") == color ||
    //    $('#c3r2' && '#c4r3' && '#c5r4' && '#c6r5').attr("fill") == color) {
    //    return true;
    //
    //}
    //return false;    
//}

//function checkForWinnerDiagonalUL(color) {
    //if ($('#c6r0' && '#c5r1' && '#c4r2' && '#c3r3').attr("fill") == color) {
        //return true;

    //}
    //return false;
//}


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
    if (checkForWinnerDiagonalUR(color)) {
        console.log("You win Diagonal to Right");
    }
    //if (checkForWinnerDiagonalUL(color)) {
       // console.log("You win Diagonal to Left")
   // }
}



