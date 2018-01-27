var gameStates = {
    noGame : "nogame",
    gameInProgress : "gameinprogress",
    gameError : "gameerror",
    gameOver : "gameover",
};

var State = gameStates.noGame;
var gameData = {};
var $game = $("#game");
var $messages = $("#messages");
var $control = $("#control");
var $status = $("#status");

function clearMessages() {
    "use strict";
    $messages.empty();
    return;
}

function clearStatus() {
    "use strict";
    $status.empty();
    return;
}

function clearControl() {
    "use strict";
    $control.empty();
    return;
}

function clearBoard() {
    "use strict";
    $game.empty();
    return;
}
function gameError(error) {
    "use strict";
    clearMessages();
    var $div = $("<div class='error'></div>");
    $div.html(error.error);
    $messages.append($div);
    return;
}

function ChangeGameState(newState) {
    "use strict";
    if (Object.values(gameStates).indexOf(newState) > -1) {
        State = newState;
        return;
    } else {
        State = gameStates.gameError;
        gameError({"error" : "GameState eror! Invalid state: " + newState});
        return;
    }
}



function gameMessage(message) {
    "use strict";
    clearMessages();
    var $div = $("<div class='message'></div>");
    $div.html(message.message);
    $messages.append($div);
    return;
}


function ajaxStartNewGame(){
    "use strict";
    clearMessages();
    var boardSize = $("#gridSize").val();
    if (boardSize === undefined)
    {
        gameError({"error" : "There is no gridSize input. The page may have loaded incorectly. please refresh!"});
        return;
    }
    gameMessage({"message" :"Please wait..."});
    $.get("/newgame/" + boardSize)
        .done(function(data){
            if (data.error !== undefined) {
                gameError(data);
                return;
            }
            gameData = data;
            ChangeGameState(gameStates.gameInProgress);
            setupPage();
            return;
        })
        .fail(function(error) {
            if (error.status === 404) {
                gameError({"error" : "Bad gridSize was given. GridSize must be a number, minimum 3!"});
                return;
            }
        });
}

function ajaxSendData(){
    "use strict";
    clearMessages();
    gameMessage({"message" :"Please wait..."});
    $.post("/move",
        {
            board : gameData.board,
            boardSize : gameData.boardSize,
            player: gameData.player
        })
        .done(function(data){
            if (data.error !== undefined) {
                gameError(data);
                return;
            }
            gameData = data;
            if (gameData.over === true) {
                ChangeGameState(gameStates.gameOver);
                setupPage();
                return;
            }
            ChangeGameState(gameStates.gameInProgress);
            setupPage();
            return;
        })
        .fail(function(error) {
            console.log(error);
            if (error.status === 404) {
                gameError({"error" : "Bad gridSize was given. GridSize must be a number, minimum 3!"});
                return;
            }
        });
}

function getMarkHTML(mark){
    "use strict";
    return $("<img src='/static/images/" + mark + ".jpg'/>");
}

function setupBoardEvents() {
    "use strict";
    if (State === gameStates.gameInProgress) {
        var $tds = $("td");
        $tds.click(function() {
            var self = $(this);
            var coord = self.data("coordinates");
            updateBoardData(coord, gameData.player);
            ajaxSendData();
            return;
        });
    }
    return;
}

function drawBoard() {
    "use strict";
    clearBoard();
    var $table = $("<table></table>");
    for (var i = 0; i < gameData.boardSize; i++) {
        var $tr = $("<tr></tr>");
        for (var j = 0; j < gameData.boardSize; j++) {
            var coordinates = [i, j];
            var $td = $("<td></td>");
            $td.data("coordinates", coordinates);
            if (gameData.board[i][j] !== false) {
                $td.append(getMarkHTML(gameData.board[i][j])); 
            }
            $tr.append($td);
        }
        $table.append($tr);
        
    }
    $game.append($table);
    setupBoardEvents();
    return;
}



function updateBoardData(coord, playerMark) {
    "use strict";
    if (Array.isArray(coord) === false) {
        gameError({"error" : "updateBoardData: coord is not an array!"});
        return;
    }
    if (coord.length !== 2) {
        gameError({"error" : "updateBoardData: coord must have only 2 numbers!"});
        return;
    }
    gameData.board[coord[0]][coord[1]] = playerMark;
    drawBoard();
    return;

}



function addControl() {
    "use strict";
    var $message;
    if (State === gameStates.noGame) {
        $message = $("<h2>Welcome to tic-tac-toe!</h2><h4>To begin, please state the size of the grid and press New Game.</h4>");
    } else if (State === gameStates.gameInProgress) {
        $message = $("<h4>To begin anew, please state the size of the grid and press New Game.</h4>");
    } else if (State === gameStates.gameOver) {
        $message = $("<h4>To begin again, please state the size of the grid and press New Game.</h4>");
    } else if (State === gameStates.gameError) {
        $message = $("<h4>There seems to be an unforseen error. please refresh the page.</h4>");
    }
    $control.append($message);
    var $div = $("<div class='center'></div>");
    var $input = $("<input  type='number' min='3' id='gridSize' value='3'/>");
    var $button = $("<button id='begin'>New Game</button>");
    $button.click(ajaxStartNewGame);
    $div.append($input);
    $div.append($button);
    $control.append($div);
    return;
}


function displayStatus() {
    "use strict";
    var $message;
    if (State === gameStates.gameInProgress) {
        $message = $("<h3>It's your turn. You are: " + gameData.player + "</h3>");
    } else if (State === gameStates.gameOver) {
        $message = $("<div><h3>Game over!</h3></div>");
        var winnerTitle = gameData.winner + " won!";
        if (gameData.player === gameData.winner) {
            winnerTitle += "You have won! Congratualtion!";
        } else {
            winnerTitle += "You have lost! Better luck next time:)!";
        }
        var $winner = $("<h4>" + winnerTitle + "</h4>");
        $message.append($winner);

    }
    $status.append($message);
    return;
}

function setupNewGame() {
    "use strict";
    clearControl();
    addControl();
    return;
}

function setupGameinProgress() {
    "use strict";
    clearControl();
    addControl();
    drawBoard();
    clearStatus();
    displayStatus();
    clearMessages();

}

function setupGameOver(){
    "use strict";
    clearControl();
    addControl();
    drawBoard();
    clearStatus();
    displayStatus();
    clearMessages();

}

function setupPage() {
    "use strict";
    
    if (State === gameStates.noGame) {
        setupNewGame();
        return;
    }
    if (State === gameStates.gameInProgress) {
        setupGameinProgress();
        return;
    }
    if (State === gameStates.gameOver)
    {
        setupGameOver();
        return;
    }


}


$(function() {
    "use strict";
    setupPage();
    return;
});