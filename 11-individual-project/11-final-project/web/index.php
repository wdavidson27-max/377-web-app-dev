<?php
require_once __DIR__ . '/session.php';

start_app_session();

$seatCount = 8;
$isLoggedIn = isset($_SESSION['user_id']);
$loggedInPlayerEmail = $_SESSION['user_email'] ?? '';
$loggedInPlayerName = $isLoggedIn ? explode('@', $loggedInPlayerEmail)[0] : '';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eight-Seat Poker Table</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Manrope:wght@400;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./styles.css?v=<?= time() ?>" />
  </head>
  <body>
    <main class="table-scene">
      <section class="table-header-bar" aria-label="User session">
        <?php if ($isLoggedIn): ?>
          <button type="button" class="login-status" id="userStatsButton">
            <?= htmlspecialchars($loggedInPlayerName, ENT_QUOTES, 'UTF-8') ?>
          </button>
          <div class="user-stats-panel hidden" id="userStatsPanel">
            <strong>All-time stats</strong>
            <span>Winnings: $<span id="totalWinnings">0</span></span>
            <span>Losses: $<span id="totalLosses">0</span></span>
          </div>
        <?php else: ?>
          <a class="login-link" href="./login.php">Log In</a>
        <?php endif; ?>
      </section>
      <section class="table-wrap" aria-label="Poker table">
        <div class="table-surface">
          <div class="table-rim"></div>

          <?php for ($seat = 1; $seat <= $seatCount; $seat++) : ?>
            <?php $isLoggedInSeat = $isLoggedIn && $seat === 1; ?>
            <div
              class="seat seat-<?= $seat ?>"
              data-seat-number="<?= $seat ?>"
              data-default-name="Player <?= $seat ?>"
              role="button"
              tabindex="0"
              aria-label="Seat <?= $seat ?>"
            >
              <span class="seat-tag">Seat <?= $seat ?></span>
              <span class="seat-role-markers" aria-hidden="true"></span>
              <strong><?= htmlspecialchars($isLoggedInSeat ? $loggedInPlayerName : "Player $seat", ENT_QUOTES, 'UTF-8') ?></strong>
              <small class="seat-buyin">Buy-in: --</small>
              <small class="seat-action-status"></small>
              <span class="seat-cards" aria-live="polite"></span>
              <span class="seat-actions hidden" aria-label="Preflop actions" hidden>
                <button type="button" class="seat-action-button" data-action="check">Check</button>
                <button type="button" class="seat-action-button" data-action="raise">Raise</button>
                <button type="button" class="seat-action-button" data-action="fold">Fold</button>
              </span>
            </div>
          <?php endfor; ?>

          <div class="deck-stack" aria-label="Face-down 52 card deck">
            <div class="deck-card">
              <div class="deck-back-ornament deck-back-corner top-left"></div>
              <div class="deck-back-ornament deck-back-corner top-right"></div>
              <div class="deck-back-ornament deck-back-corner bottom-left"></div>
              <div class="deck-back-ornament deck-back-corner bottom-right"></div>
              <div class="deck-back-center"></div>
            </div>
          </div>

          <div class="community-cards hidden" aria-label="Flop cards">
            <span class="community-card-row"></span>
          </div>

          <button type="button" class="deal-button" aria-label="Deal cards">
            Deal
          </button>
        </div>
      </section>
    </main>

    <script>
      const userIsLoggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;
      const statsEndpoint = "./stats.php";
      const dealEndpoint = "./deal.php";
      const dealButton = document.querySelector(".deal-button");
      const userStatsButton = document.querySelector("#userStatsButton");
      const userStatsPanel = document.querySelector("#userStatsPanel");
      const deckStack = document.querySelector(".deck-stack");
      const communityCards = document.querySelector(".community-cards");
      const seats = document.querySelectorAll(".seat");
      const humanSeatNumber = "1";
      const computerMinBuyin = 50;
      const computerMaxBuyin = 500;
      const smallBlindAmount = 5;
      const bigBlindAmount = 10;
      let dealerSeatNumber = 1;
      let handHasBeenDealt = false;
      let activeHandSeats = [];
      let currentActionIndex = 0;
      let currentRaiseAmount = "";
      let raiserSeat = null;
      let isCallRound = false;
      let flopCards = [];
      let turnCard = null;
      let riverCard = null;
      let actionsRemaining = 0;
      let bettingRound = "preflop";
      let potAmount = 0;
      let currentBetAmount = 0;

      if (userStatsButton) {
        userStatsButton.addEventListener("click", () => {
          userStatsPanel.classList.toggle("hidden");
          loadUserStats();
        });
      }

      // Player can now click on a seat and enter their name
      seats.forEach((seat) => {
        seat.addEventListener("click", () => {
          if (!requireLoginToPlay()) {
            return;
          }

          if (!isHumanSeat(seat)) {
            window.alert("This seat is controlled by the computer.");
            return;
          }

          const currentName = seat.querySelector("strong").textContent.trim();
          const defaultName = seat.dataset.defaultName;
          const suggestedName = currentName === defaultName ? "" : currentName;
          const enteredName = window.prompt(
          
            `Enter your name Player ${seat.dataset.seatNumber}:`,
            suggestedName
          ); 

          if (enteredName === null) {
            return;
          }
          
          const cleanedName = enteredName.trim();
          seat.querySelector("strong").textContent = cleanedName || defaultName;

          // Buy in stuff
          if (!cleanedName) {
            seat.querySelector(".seat-buyin").textContent = "Buy-in: --";
            delete seat.dataset.buyin;
            return;
          }

          const currentBuyin = seat.dataset.buyin || "";
          const enteredBuyin = window.prompt(
            `Enter buy-in amount for ${cleanedName}:`,
            currentBuyin
          );

          if (enteredBuyin === null) {
            return;
          }
          
          const cleanedBuyin = enteredBuyin.trim();

          if (cleanedBuyin !== "" && !isValidBuyinAmount(cleanedBuyin)) {
            window.alert("Buy-in amount can only contain numbers.");
            return;
          }

          seat.querySelector(".seat-buyin").textContent = cleanedBuyin
            ? `Buy-in: $${cleanedBuyin}`
            : "Buy-in: --";
          seat.dataset.buyin = cleanedBuyin;
        });
      });
      
      document.querySelectorAll(".seat-action-button").forEach((button) => {
        button.addEventListener("click", (event) => {
          event.stopPropagation();
          handlePlayerAction(button);
        });
      });

      // Deal Button
      dealButton.addEventListener("click", () => {
        if (!requireLoginToPlay()) {
          return;
        }

        if (handHasBeenDealt) {
          dealerSeatNumber = getSeatToRight(dealerSeatNumber);
        }
        
        renderRoleMarkers();
        communityCards.querySelector(".community-card-row").innerHTML = "";
        communityCards.classList.add("hidden");
        communityCards.classList.remove("is-visible");
        communityCards.style.display = "";
        askLoggedInSeatForBuyin();
        fillComputerSeats();

        const humanSeat = document.querySelector(`.seat[data-seat-number="${humanSeatNumber}"]`);
        if ((humanSeat.dataset.buyin || "").trim() === "") {
          window.alert("Enter your buy-in before dealing.");
          return;
        }

        seats.forEach((seat) => {
          seat.querySelector(".seat-cards").innerHTML = "";
          seat.querySelectorAll(".raise-chip").forEach((chip) => chip.remove());
          clearSeatActionStatus(seat);
          seat.holeCards = [];
          seat.roundBet = 0;
          hideSeatActions(seat);
               
        });
        // Confirms that there is a name and buy in amount for the player in that seat
        const activeSeats = Array.from(seats).filter((seat) => {
          const playerName = seat.querySelector("strong").textContent.trim();
          const buyin = (seat.dataset.buyin || "").trim();
          return playerName !== seat.dataset.defaultName && buyin !== "";          
        });

        if (activeSeats.length === 0) {
          window.alert("Add a player name and buy-in before dealing.");
          return;
        }
        
        deckStack.style.display = "none";

        const formData = new FormData();
        formData.append("player_count", activeSeats.length);   
        fetch(dealEndpoint, {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (!data.ok) {
              window.alert(data.message || "Could not deal cards.");
              return;
            }
            
            dealCardsToSeats(activeSeats, data.deals).then(() => {
              activeHandSeats = activeSeats;
              currentActionIndex = 0;
              currentRaiseAmount = "";
              currentBetAmount = bigBlindAmount;
              raiserSeat = null;
              isCallRound = false;
              bettingRound = "preflop";
              potAmount = 0;
              flopCards = Array.isArray(data.flop) ? data.flop : [];
              turnCard = data.turn || null;
              riverCard = data.river || null;
              postBlinds(activeSeats);
              actionsRemaining = activeSeats.length;
              isCallRound = true;
              showCurrentPlayerActions();
              handHasBeenDealt = true;
            });
          })
          .catch(() => {
            window.alert("Could not connect to the deal endpoint.");
        });
      });

      function askLoggedInSeatForBuyin() {
        const loggedInSeat = document.querySelector(`.seat[data-seat-number="${humanSeatNumber}"]`);
        const playerName = loggedInSeat.querySelector("strong").textContent.trim();

        if (playerName === loggedInSeat.dataset.defaultName || (loggedInSeat.dataset.buyin || "").trim() !== "") {
          return;
        }

        const enteredBuyin = window.prompt(
          `Enter buy-in amount for ${playerName}:`,
          ""
        );

        if (enteredBuyin === null) {
          return;
        }

        const cleanedBuyin = enteredBuyin.trim();

        if (cleanedBuyin !== "" && !isValidBuyinAmount(cleanedBuyin)) {
          window.alert("Buy-in amount can only contain numbers.");
          return;
        }

        loggedInSeat.querySelector(".seat-buyin").textContent = cleanedBuyin
          ? `Buy-in: $${cleanedBuyin}`
          : "Buy-in: --";
        loggedInSeat.dataset.buyin = cleanedBuyin;
      }

      function fillComputerSeats() {
        seats.forEach((seat) => {
          if (isHumanSeat(seat)) {
            return;
          }

          const seatNumber = seat.dataset.seatNumber;
          const computerName = `Computer ${seatNumber}`;
          seat.querySelector("strong").textContent = computerName;
          seat.dataset.computer = "true";

          if ((seat.dataset.buyin || "").trim() === "") {
            const computerBuyin = getRandomComputerBuyin();
            seat.dataset.buyin = String(computerBuyin);
            seat.querySelector(".seat-buyin").textContent = `Buy-in: $${computerBuyin}`;
          }
        });
      }

      function getRandomComputerBuyin() {
        return Math.floor(Math.random() * (computerMaxBuyin - computerMinBuyin + 1)) + computerMinBuyin;
      }

      function isHumanSeat(seat) {
        return seat.dataset.seatNumber === humanSeatNumber;
      }

      function isComputerSeat(seat) {
        return seat.dataset.computer === "true";
      }

      function loadUserStats() {
        if (!userIsLoggedIn) {
          return;
        }

        fetch(statsEndpoint)
          .then((response) => response.json())
          .then((data) => {
            if (!data.ok) {
              return;
            }

            document.querySelector("#totalWinnings").textContent = data.total_winnings;
            document.querySelector("#totalLosses").textContent = data.total_losses;
          });
      }

      function recordUserStats(winnings, losses) {
        if (!userIsLoggedIn || (winnings <= 0 && losses <= 0)) {
          return;
        }

        const formData = new FormData();
        formData.append("winnings", winnings);
        formData.append("losses", losses);

        fetch(statsEndpoint, {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (!data.ok || !userStatsPanel || userStatsPanel.classList.contains("hidden")) {
              return;
            }

            document.querySelector("#totalWinnings").textContent = data.total_winnings;
            document.querySelector("#totalLosses").textContent = data.total_losses;
          });
      }

      function isValidBuyinAmount(amount) {
        return /^[0-9]+$/.test(amount);
      }

      function postBlinds(activeSeats) {
        resetRoundBets();
        const smallBlindSeatNumber = getSeatToRight(dealerSeatNumber);
        const bigBlindSeatNumber = getSeatToRight(smallBlindSeatNumber);
        const smallBlindSeat = activeSeats.find((seat) => Number(seat.dataset.seatNumber) === smallBlindSeatNumber);
        const bigBlindSeat = activeSeats.find((seat) => Number(seat.dataset.seatNumber) === bigBlindSeatNumber);

        if (smallBlindSeat) {
          postForcedBet(smallBlindSeat, smallBlindAmount, "Small blind");
        }

        if (bigBlindSeat) {
          postForcedBet(bigBlindSeat, bigBlindAmount, "Big blind");
          raiserSeat = bigBlindSeat;
        }

        currentBetAmount = bigBlindAmount;
        currentRaiseAmount = String(bigBlindAmount);
      }

      function postForcedBet(seat, amount, label) {
        const blindAmount = Math.min(Number(seat.dataset.buyin), amount);
        subtractFromBuyin(seat, blindAmount);
        seat.roundBet = (seat.roundBet || 0) + blindAmount;
        showSeatActionStatus(seat, `${label} $${blindAmount}`);
        showRaiseChip(seat, blindAmount);
      }

      function resetRoundBets() {
        activeHandSeats.forEach((seat) => {
          seat.roundBet = 0;
          clearSeatActionStatus(seat);
        });
      }

      function getAmountToCall(seat) {
        return Math.max(0, currentBetAmount - (seat.roundBet || 0));
      }

      function createFaceUpCard(card) {
        const cardElement = document.createElement("span");
        cardElement.className = `mini-card mini-card-face ${card.color === "red" ? "mini-card-red" : "mini-card-black"}`;
        cardElement.innerHTML = `<span>${card.rank}</span><small>${card.suit}</small>`; 
        return cardElement;
      }

      function createFaceDownCard() {
        const cardElement = document.createElement("span");
        cardElement.className = "mini-card mini-card-back";
        return cardElement;
      }

      // Figures out what button player clicked on
      function handlePlayerAction(button) {
        if (!requireLoginToPlay()) {
          return;
        }

        const action = button.dataset.action;
        const activeSeat = activeHandSeats[currentActionIndex];

        if (!activeSeat) {
          return;
        }

        if (action === "raise") {
          const playerName = activeSeat.querySelector("strong").textContent.trim();
          const amountToCall = getAmountToCall(activeSeat);
          const raiseAmount = window.prompt(`How much does ${playerName} want to raise?`);
          
          if (raiseAmount === null) {
            return;
          }
          
          const cleanedRaiseAmount = raiseAmount.trim();

          if (cleanedRaiseAmount === "") {
            window.alert("Enter a raise first.");            
            return;
          }
          
          const totalAmount = amountToCall + Number(cleanedRaiseAmount);
          // If the player raised then they need the miney taken
          if (!subtractFromBuyin(activeSeat, totalAmount)) {
            return;
          }

          activeSeat.roundBet = (activeSeat.roundBet || 0) + totalAmount;
          currentBetAmount = activeSeat.roundBet;
          currentRaiseAmount = String(currentBetAmount);
          raiserSeat = activeSeat;
          isCallRound = true;
          actionsRemaining = activeHandSeats.length - 1;
          showSeatActionStatus(activeSeat, `Raised $${cleanedRaiseAmount}`);
          showRaiseChip(activeSeat, currentBetAmount);
          currentActionIndex = (currentActionIndex + 1) % activeHandSeats.length;
          showCurrentPlayerActions();
          return;
        } 
        // If all fold the hand ends
        if (action === "fold") {
          const foldedPlayerName = activeSeat.querySelector("strong").textContent.trim();
          activeSeat.querySelector(".seat-cards").innerHTML = "";
          showSeatActionStatus(activeSeat, "Folded");
          activeHandSeats.splice(currentActionIndex, 1);
          continueAfterFold(foldedPlayerName);
          return;
        } else if (action === "call") {
          const amountToCall = getAmountToCall(activeSeat);

          if (!subtractFromBuyin(activeSeat, amountToCall)) {
            return;
          }

          activeSeat.roundBet = (activeSeat.roundBet || 0) + amountToCall;
          showSeatActionStatus(activeSeat, `Called $${amountToCall}`);
          actionsRemaining -= 1;
          currentActionIndex = (currentActionIndex + 1) % activeHandSeats.length;
          advancePreflopAction();
          return;
        } else {
          showSeatActionStatus(activeSeat, "Checked");
          actionsRemaining -= 1;
          currentActionIndex = (currentActionIndex + 1) % activeHandSeats.length;
          advancePreflopAction();
          return;
        }
      }

      function requireLoginToPlay() {
        if (userIsLoggedIn) {
          return true;
        }

        window.alert("You must log in before playing.");
        return false;
      }

      function advancePreflopAction() {
        if (actionsRemaining <= 0 && activeHandSeats.length >= 2) {
          completePreflopAction();
          return;
        }

        if (isCallRound && activeHandSeats[currentActionIndex] === raiserSeat) {
          completePreflopAction();
          return;
        }

        if (actionsRemaining <= 0) {
          completePreflopAction();
          return;
        }

        showCurrentPlayerActions();
      }
      // Continues to flop if at least 2 players are in 
      function completePreflopAction() {
        hideAllSeatActions();
        isCallRound = false;

        if (activeHandSeats.length < 2) {
          window.alert("Hand is over.");
          return;
        }

        if (bettingRound === "preflop") {
          showFlopCards();
          startBettingRound("flop");
          return;
        }

        if (bettingRound === "flop") {
          showTurnCard();
          startBettingRound("turn");
          return;
        }

        if (bettingRound === "turn") {
          showRiverCard();
          startBettingRound("river");
          return;
        }

        if (bettingRound === "river") {
          hideAllSeatActions();
          determineWinnerAndAwardPot();
          return;
        }
      }

      function startBettingRound(roundName) {
        bettingRound = roundName;
        currentActionIndex = 0;
        currentRaiseAmount = "";
        currentBetAmount = 0;
        raiserSeat = null;
        isCallRound = false;
        actionsRemaining = activeHandSeats.length;
        resetRoundBets();
        showCurrentPlayerActions();
      }

      function showFlopCards() {
        const communityCardRow = communityCards.querySelector(".community-card-row");
        communityCardRow.innerHTML = "";

        if (flopCards.length < 3) {
          window.alert("The flop cards were not returned by deal.php.");
          return;
        }

        flopCards.forEach((card) => {
          communityCardRow.append(createFaceUpCard(card));
        });

        communityCards.classList.remove("hidden");
        communityCards.classList.add("is-visible");
        communityCards.style.display = "flex";
      }

      function showTurnCard() {
        const communityCardRow = communityCards.querySelector(".community-card-row");

        if (!turnCard) {
          window.alert("The turn card was not returned by deal.php.");
          return;
        }

        communityCardRow.append(createFaceUpCard(turnCard));
      }

      function showRiverCard() {
        const communityCardRow = communityCards.querySelector(".community-card-row");

        if (!riverCard) {
          window.alert("The river card was not returned by deal.php");
          return;
        }

        communityCardRow.append(createFaceUpCard(riverCard));
        hideAllSeatActions();
      }

      function showCurrentPlayerActions() {
        hideAllSeatActions();
        const activeSeat = activeHandSeats[currentActionIndex];

        if (!activeSeat) {
          return;
        }

        if (isComputerSeat(activeSeat)) {
          window.setTimeout(() => {
            takeComputerAction(activeSeat);
          }, 450);
          return;
        }

        const seatActions = activeSeat.querySelector(".seat-actions");
        updateActionButtons(seatActions);
        seatActions.classList.remove("hidden");
        seatActions.hidden = false;
      }

      function takeComputerAction(seat) {
        if (seat !== activeHandSeats[currentActionIndex]) {
          return;
        }

        const decision = chooseComputerAction(seat);

        if (decision === "fold") {
          foldComputerSeat(seat);
          return;
        }

        if (decision === "raise") {
          raiseComputerSeat(seat);
          return;
        }

        if (decision === "call") {
          callComputerSeat(seat);
          return;
        }

        checkComputerSeat(seat);
      }

      function chooseComputerAction(seat) {
        const strength = getComputerHandStrength(seat);
        const randomNumber = Math.random();

        if (isCallRound) {
          if (strength >= 0.75) {
            return randomNumber < 0.35 ? "raise" : "call";
          }

          if (strength >= 0.45) {
            return randomNumber < 0.12 ? "raise" : "call";
          }

          return randomNumber < 0.65 ? "fold" : "call";
        }

        if (strength >= 0.78) {
          return randomNumber < 0.45 ? "raise" : "check";
        }

        if (strength >= 0.5) {
          return randomNumber < 0.18 ? "raise" : "check";
        }

        return randomNumber < 0.08 ? "raise" : "check";
      }

      function foldComputerSeat(seat) {
        const foldedPlayerName = seat.querySelector("strong").textContent.trim();
        seat.querySelector(".seat-cards").innerHTML = "";
        showSeatActionStatus(seat, "Folded");
        activeHandSeats.splice(currentActionIndex, 1);
        continueAfterFold(foldedPlayerName);
      }

      function callComputerSeat(seat) {
        const amountToCall = getAmountToCall(seat);

        if (!subtractFromBuyin(seat, amountToCall)) {
          foldComputerSeat(seat);
          return;
        }

        seat.roundBet = (seat.roundBet || 0) + amountToCall;
        showSeatActionStatus(seat, `Called $${amountToCall}`);
        actionsRemaining -= 1;
        currentActionIndex = (currentActionIndex + 1) % activeHandSeats.length;
        advancePreflopAction();
      }

      function checkComputerSeat(seat) {
        showSeatActionStatus(seat, "Checked");
        actionsRemaining -= 1;
        currentActionIndex = (currentActionIndex + 1) % activeHandSeats.length;
        advancePreflopAction();
      }

      function raiseComputerSeat(seat) {
        const amountToCall = getAmountToCall(seat);
        const raiseAmount = getComputerRaiseAmount(seat);
        const totalAmount = amountToCall + raiseAmount;

        if (!subtractFromBuyin(seat, totalAmount)) {
          if (isCallRound) {
            callComputerSeat(seat);
          } else {
            checkComputerSeat(seat);
          }
          return;
        }

        seat.roundBet = (seat.roundBet || 0) + totalAmount;
        currentBetAmount = seat.roundBet;
        currentRaiseAmount = String(currentBetAmount);
        raiserSeat = seat;
        isCallRound = true;
        actionsRemaining = activeHandSeats.length - 1;
        showSeatActionStatus(seat, `Raised $${raiseAmount}`);
        showRaiseChip(seat, currentBetAmount);
        currentActionIndex = (currentActionIndex + 1) % activeHandSeats.length;
        showCurrentPlayerActions();
      }

      function getComputerRaiseAmount(seat) {
        const buyin = Number(seat.dataset.buyin);
        const baseRaise = bettingRound === "preflop" ? bigBlindAmount : 10;
        const maxRaise = Math.max(baseRaise, Math.min(buyin, baseRaise * 4));
        return Math.max(baseRaise, Math.floor(Math.random() * maxRaise) + 1);
      }

      function getComputerHandStrength(seat) {
        const knownCards = [
          ...(seat.holeCards || []),
          ...flopCards,
          turnCard,
          riverCard,
        ].filter(Boolean);

        if (knownCards.length >= 5) {
          return evaluatePokerHand(knownCards)[0] / 8;
        }

        return getPreflopStrength(seat.holeCards || []);
      }

      function getPreflopStrength(cards) {
        if (cards.length < 2) {
          return 0.25;
        }

        const rankValues = {
          "2": 2,
          "3": 3,
          "4": 4,
          "5": 5,
          "6": 6,
          "7": 7,
          "8": 8,
          "9": 9,
          "10": 10,
          "J": 11,
          "Q": 12,
          "K": 13,
          "A": 14,
        };
        const firstValue = rankValues[cards[0].rank];
        const secondValue = rankValues[cards[1].rank];
        const highCard = Math.max(firstValue, secondValue);
        const lowCard = Math.min(firstValue, secondValue);
        let strength = highCard / 14;

        if (firstValue === secondValue) {
          strength += 0.28;
        }

        if (cards[0].suit === cards[1].suit) {
          strength += 0.08;
        }

        if (highCard - lowCard <= 2) {
          strength += 0.06;
        }

        return Math.min(1, strength);
      }

      function updateActionButtons(seatActions) {
        const checkButton = seatActions.querySelector('[data-action="check"], [data-action="call"]');
        const raiseButton = seatActions.querySelector('[data-action="raise"]');

        if (isCallRound) {
          const activeSeat = activeHandSeats[currentActionIndex];
          checkButton.textContent = `Call $${getAmountToCall(activeSeat)}`;
          checkButton.dataset.action = "call";
          raiseButton.hidden = true;
          return;
        }

        checkButton.textContent = "Check";
        checkButton.dataset.action = "check";
        raiseButton.hidden = false;
      }

      function showRaiseChip(seat, amount) {
        seat.querySelectorAll(".raise-chip").forEach((chip) => chip.remove());

        const raiseChip = document.createElement("span");
        raiseChip.className = "raise-chip";
        raiseChip.textContent = `$${amount}`;
        seat.querySelector(".seat-role-markers").append(raiseChip);
      }

      function showSeatActionStatus(seat, message) {
        seat.querySelector(".seat-action-status").textContent = message;
      }

      function clearSeatActionStatus(seat) {
        seat.querySelector(".seat-action-status").textContent = "";
      }

      function subtractFromBuyin(seat, amount) {
        const currentBuyin = Number(seat.dataset.buyin);
        const betAmount = Number(amount);

        if (!Number.isFinite(currentBuyin) || !Number.isFinite(betAmount) || betAmount <= 0) {
          window.alert("Enter a valid dollar amount.");
          return false;
        }

        if (betAmount > currentBuyin) {
          window.alert("That player does not have enough buy-in left.");
          return false;
        }

        const updatedBuyin = currentBuyin - betAmount;
        seat.dataset.buyin = String(updatedBuyin);
        seat.querySelector(".seat-buyin").textContent = `Buy-in: $${updatedBuyin}`;
        potAmount += betAmount;

        if (isHumanSeat(seat)) {
          recordUserStats(0, betAmount);
        }

        return true;
      }

      function continueAfterFold(foldedPlayerName) {
        actionsRemaining -= 1;

        if (activeHandSeats.length <= 1) {
          endHandAfterFold(foldedPlayerName);
          return;
        }

        if (currentActionIndex >= activeHandSeats.length) {
          currentActionIndex = 0;
        }

        advancePreflopAction();
      }

      function endHandAfterFold(foldedPlayerName) {
        hideAllSeatActions();
        revealRemainingHands();

        if (activeHandSeats.length > 0 && potAmount > 0) {
          const winningSeat = activeHandSeats[0];
          const winnerName = winningSeat.querySelector("strong").textContent.trim();
          addToBuyin(winningSeat, potAmount);

          window.alert(`${foldedPlayerName} folded. ${winnerName} won $${potAmount} from the pot.`);
        } else {
          window.alert(`${foldedPlayerName} folded. Hand is over.`);
        }

        potAmount = 0;
        window.setTimeout(cleanUpFinishedHand, 3500);
      }

      function determineWinnerAndAwardPot() {
        revealRemainingHands();
        const communityHand = [...flopCards, turnCard, riverCard].filter(Boolean);
        const playerScores = activeHandSeats.map((seat) => {
          return {
            seat,
            score: evaluatePokerHand([...(seat.holeCards || []), ...communityHand]),
          };
        });

        const bestScore = playerScores.reduce((best, player) => {
          return compareHandScores(player.score, best.score) > 0 ? player : best;
        }).score;

        const winners = playerScores.filter((player) => {
          return compareHandScores(player.score, bestScore) === 0;
        });
        const winningHandName = getHandName(bestScore[0]);

        const splitAmount = Math.floor(potAmount / winners.length);
        const leftoverAmount = potAmount % winners.length;
        winners.forEach((winner, index) => {
          const payout = splitAmount + (index < leftoverAmount ? 1 : 0);
          addToBuyin(winner.seat, payout);
        });

        const winnerNames = winners.map((winner) => {
          return winner.seat.querySelector("strong").textContent.trim();
        }).join(", ");

        const winnerMessage = `${winnerNames} won $${potAmount} from the pot with ${winningHandName}.`;
        potAmount = 0;
        window.setTimeout(() => {
          window.alert(winnerMessage);
          window.setTimeout(cleanUpFinishedHand, 3500);
        }, 1000);
      }

      function revealRemainingHands() {
        activeHandSeats.forEach((seat) => {
          const seatCards = seat.querySelector(".seat-cards");
          seatCards.innerHTML = "";

          (seat.holeCards || []).forEach((card) => {
            seatCards.append(createFaceUpCard(card));
          });
        });
      }

      function addToBuyin(seat, amount) {
        const currentBuyin = Number(seat.dataset.buyin);
        const updatedBuyin = currentBuyin + amount;
        seat.dataset.buyin = String(updatedBuyin);
        seat.querySelector(".seat-buyin").textContent = `Buy-in: $${updatedBuyin}`;

        if (isHumanSeat(seat)) {
          recordUserStats(amount, 0);
        }
      }

      function evaluatePokerHand(cards) {
        const rankValues = {
          "2": 2,
          "3": 3,
          "4": 4,
          "5": 5,
          "6": 6,
          "7": 7,
          "8": 8,
          "9": 9,
          "10": 10,
          "J": 11,
          "Q": 12,
          "K": 13,
          "A": 14,
        };
        const values = cards.map((card) => rankValues[card.rank]).sort((a, b) => b - a);
        const uniqueValues = [...new Set(values)];
        const rankCounts = new Map();
        const suitGroups = new Map();

        cards.forEach((card) => {
          const value = rankValues[card.rank];
          rankCounts.set(value, (rankCounts.get(value) || 0) + 1);

          if (!suitGroups.has(card.suit)) {
            suitGroups.set(card.suit, []);
          }

          suitGroups.get(card.suit).push(value);
        });

        const flushValues = [...suitGroups.values()]
          .find((group) => group.length >= 5)
          ?.sort((a, b) => b - a);

        if (flushValues) {
          const straightFlushHigh = getStraightHigh([...new Set(flushValues)]);

          if (straightFlushHigh) {
            return [8, straightFlushHigh];
          }
        }

        const fourKind = getRanksWithCount(rankCounts, 4)[0];
        if (fourKind) {
          return [7, fourKind, getKickers(uniqueValues, [fourKind], 1)[0]];
        }

        const threeKinds = getRanksWithCount(rankCounts, 3);
        const pairs = getRanksWithCount(rankCounts, 2);
        if (threeKinds.length > 0) {
          const pairForFullHouse = [...threeKinds.slice(1), ...pairs][0];

          if (pairForFullHouse) {
            return [6, threeKinds[0], pairForFullHouse];
          }
        }

        if (flushValues) {
          return [5, ...flushValues.slice(0, 5)];
        }

        const straightHigh = getStraightHigh(uniqueValues);
        if (straightHigh) {
          return [4, straightHigh];
        }

        if (threeKinds.length > 0) {
          return [3, threeKinds[0], ...getKickers(uniqueValues, [threeKinds[0]], 2)];
        }

        if (pairs.length >= 2) {
          return [2, pairs[0], pairs[1], ...getKickers(uniqueValues, [pairs[0], pairs[1]], 1)];
        }

        if (pairs.length === 1) {
          return [1, pairs[0], ...getKickers(uniqueValues, [pairs[0]], 3)];
        }

        return [0, ...uniqueValues.slice(0, 5)];
      }

      function getHandName(handRank) {
        const handNames = [
          "a high card",
          "one pair",
          "two pair",
          "three of a kind",
          "a straight",
          "a flush",
          "a full house",
          "four of a kind",
          "a straight flush",
        ];

        return handNames[handRank] || "a poker hand";
      }

      function cleanUpFinishedHand() {
        communityCards.querySelector(".community-card-row").innerHTML = "";
        communityCards.classList.add("hidden");
        communityCards.classList.remove("is-visible");
        communityCards.style.display = "";

        seats.forEach((seat) => {
          seat.querySelector(".seat-cards").innerHTML = "";
          seat.querySelectorAll(".raise-chip").forEach((chip) => chip.remove());
          clearSeatActionStatus(seat);
          seat.holeCards = [];
          hideSeatActions(seat);
        });

        deckStack.style.display = "";
        activeHandSeats = [];
        currentActionIndex = 0;
        currentRaiseAmount = "";
        raiserSeat = null;
        isCallRound = false;
        flopCards = [];
        turnCard = null;
        riverCard = null;
        actionsRemaining = 0;
        bettingRound = "preflop";
      }

      function getRanksWithCount(rankCounts, count) {
        return [...rankCounts.entries()]
          .filter(([, rankCount]) => rankCount === count)
          .map(([rank]) => rank)
          .sort((a, b) => b - a);
      }

      function getKickers(ranks, excludedRanks, count) {
        return ranks.filter((rank) => !excludedRanks.includes(rank)).slice(0, count);
      }

      function getStraightHigh(ranks) {
        const straightRanks = ranks.includes(14) ? [...ranks, 1] : [...ranks];
        const sortedRanks = [...new Set(straightRanks)].sort((a, b) => b - a);

        for (let index = 0; index <= sortedRanks.length - 5; index += 1) {
          const straightSlice = sortedRanks.slice(index, index + 5);

          if (straightSlice[0] - straightSlice[4] === 4) {
            return straightSlice[0] === 1 ? 5 : straightSlice[0];
          }
        }

        return 0;
      }

      function compareHandScores(firstScore, secondScore) {
        const scoreLength = Math.max(firstScore.length, secondScore.length);

        for (let index = 0; index < scoreLength; index += 1) {
          const firstValue = firstScore[index] || 0;
          const secondValue = secondScore[index] || 0;

          if (firstValue !== secondValue) {
            return firstValue - secondValue;
          }
        }

        return 0;
      }

      function hideAllSeatActions() {
        seats.forEach((seat) => {
          hideSeatActions(seat);
        });
      }

      function hideSeatActions(seat) {
        const seatActions = seat.querySelector(".seat-actions");
        seatActions.classList.add("hidden");
        seatActions.hidden = true;
      }

      function dealCardsToSeats(activeSeats, deals) {
        const cardsPerPlayer = 2;
        const dealDelay = 150;
        let delay = 0;
        const dealSteps = [];

        activeSeats.forEach((seat, seatIndex) => {
          seat.holeCards = deals[seatIndex] || [];
        });

        

        for (let cardIndex = 0; cardIndex < cardsPerPlayer; cardIndex += 1) {
          activeSeats.forEach((seat, seatIndex) => {
            const card = deals[seatIndex]?.[cardIndex];

            if (!card) {
              return;
            }
            
            delay += dealDelay;
            dealSteps.push(new Promise((resolve) => {
              setTimeout(() => {
                const cardElement = isHumanSeat(seat) ? createFaceUpCard(card) : createFaceDownCard();
                seat.querySelector(".seat-cards").append(cardElement);
                resolve();
              }, delay);
            }));
          });
        }

        return Promise.all(dealSteps);
      }
      
      function getSeatToRight(seatNumber) {
        return seatNumber === 1 ? seats.length : seatNumber - 1;
      }

      function renderRoleMarkers() {
        seats.forEach((seat) => {
          seat.querySelector(".seat-role-markers").innerHTML = "";
        });

        const smallBlindSeat = getSeatToRight(dealerSeatNumber);
        const bigBlindSeat = getSeatToRight(smallBlindSeat);

        addRoleMarker(dealerSeatNumber, "dealer-chip", "D");
        addRoleMarker(smallBlindSeat, "blind-chip small-blind-chip", "SB");
        addRoleMarker(bigBlindSeat, "blind-chip big-blind-chip", "BB");
      }

      function addRoleMarker(seatNumber, className, label) {
        const seat = document.querySelector(`.seat[data-seat-number="${seatNumber}"]`);
        const marker = document.createElement("span");
        marker.className = className;
        marker.textContent = label;
        seat.querySelector(".seat-role-markers").append(marker);
        
      }

      renderRoleMarkers();
    </script>
  </body>
</html>
