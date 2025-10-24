var turn = false;
var gameOver = false;


function dropChip(column) {

    if (gameOver) return;

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
        alert(color + " Wins in a Column!");
        gameOver = true;    
        return true;
        
    }
    if ($('#c' + column + 'r4').attr("fill") == color && 
        $('#c' + column + 'r3').attr("fill") == color &&
        $('#c' + column + 'r2').attr("fill") == color &&  
        $('#c' + column + 'r1').attr("fill") == color) {
        alert(color + " Wins in a Column!");
        gameOver = true;    
        return true;
        
    }
    if ($('#c' + column + 'r3').attr("fill") == color && 
        $('#c' + column + 'r2').attr("fill") == color &&
        $('#c' + column + 'r1').attr("fill") == color &&  
        $('#c' + column + 'r0').attr("fill") == color) {
        alert(color + " Wins in a Column!");
        gameOver = true;    
        return true;
        
    }
    return false;

}

function checkForWinnerinRow(color, row) {
    if ($('#c6r' + row).attr("fill") == color && 
        $('#c5r' + row).attr("fill") == color &&
        $('#c4r' + row).attr("fill") == color &&  
        $('#c3r' + row).attr("fill") == color) {
        alert(color + " Wins in a Row!");
        gameOver = true;    
        return true;
        
    }
    if ($('#c5r' + row).attr("fill") == color && 
        $('#c4r' + row).attr("fill") == color &&
        $('#c3r' + row).attr("fill") == color &&  
        $('#c2r' + row).attr("fill") == color) {
        alert(color + " Wins in a Row!");
        gameOver = true;    
        return true;
        
    }
    if ($('#c4r' + row).attr("fill") == color && 
        $('#c3r' + row).attr("fill") == color &&
        $('#c2r' + row).attr("fill") == color &&  
        $('#c1r' + row).attr("fill") == color) {
        alert(color + " Wins in a Row!");
        gameOver = true;    
        return true;
        
    }
    if ($('#c3r' + row).attr("fill") == color && 
        $('#c2r' + row).attr("fill") == color &&
        $('#c1r' + row).attr("fill") == color &&  
        $('#c0r' + row).attr("fill") == color) {
        alert(color + " Wins in a Row!");
        gameOver = true;    
        return true;
        
    }
    return false;
}

function checkForWinnerDiagonalUR(color) {
    for (let c = 0; c <= 3; c++) {
        for (let r = 0; r <= 2; r++) {
            if ($('#c' + c + 'r' + r).attr("fill") == color &&
                $('#c' + (c + 1) + 'r' + (r + 1)).attr("fill") == color &&
                $('#c' + (c + 2) + 'r' + (r + 2)).attr("fill") == color &&
                $('#c' + (c + 3) + 'r' + (r + 3)).attr("fill") == color) {
                alert(color + " Wins Diagonally!");
                gameOver = true;
                return true;
            }
        }
    }
    return false;
}

function checkForWinnerDiagonalUL(color) {
    for (let c = 3; c < 7; c++) {
        for (let r = 0; r <= 2; r++) {
            if ($('#c' + c + 'r' + r).attr("fill") == color &&
                $('#c' + (c - 1) + 'r' + (r + 1)).attr("fill") == color &&
                $('#c' + (c - 2) + 'r' + (r + 2)).attr("fill") == color &&
                $('#c' + (c - 3) + 'r' + (r + 3)).attr("fill") == color) {
                alert(color + " Wins Diagonally!");
                gameOver = true;
                return true;
            }
        }
    }
    return false;
}


function checkForWinner(color) {
    for (let column = 0; column < 7; column++) {
        if (checkForWinnerInColumn(color, column)) {
            console.log("You won in column " + column);
            return;
        }
    }
    for (let row = 0; row < 6; row++) {
        if (checkForWinnerinRow(color, row)) {
            console.log("You won in row " + row);
            return;
        }
    }
    if (checkForWinnerDiagonalUR(color)) {
        console.log("You win Diagonal to Right");
        return;
    }
    if (checkForWinnerDiagonalUL(color)) {
        console.log("You win Diagonal to Left");
        return;
    }
}

function resetBoard() {
    for (let c = 0; c < 7; c++) {
        for (let r = 0; r < 6; r++) {
            $('#c' + c + 'r' + r).attr("fill", "white");
        }
    }
    turn = false;
    gameOver = false;
    console.log("Board reset!");
}

window.onload = function() {
    alert(
        "Welcome to Connect Four!\n\n" +
        "How to Play:\n" +
        "1 - Click on a column to drop your chip.\n" +
        "2 - This is a two player game, every time you drop a chip the player switches\n" +
        "3 - Player 1 is yellow and Player 2 is red. Yellow goes first \n"+
        "4 - Get four in a row (vertically, horizontally, or diagonally) to win!\n\n" +
        "5 - After someone wins, click the reset board button to play again" +
        " Click OK to start the game. Good luck!"
    );
};


