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
          <span class="login-status"><?= htmlspecialchars($loggedInPlayerName, ENT_QUOTES, 'UTF-8') ?></span>
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
      const dealEndpoint = "./deal.php";
      const dealButton = document.querySelector(".deal-button");
      const deckStack = document.querySelector(".deck-stack");
      const communityCards = document.querySelector(".community-cards");
      const seats = document.querySelectorAll(".seat");
      let dealerSeatNumber = 1;
      let handHasBeenDealt = false;
      let activeHandSeats = [];
      let currentActionIndex = 0;
      let currentRaiseAmount = "";
      let raiserSeat = null;
      let isCallRound = false;
      let flopCards = [];
      let actionsRemaining = 0;


      // Player can now click on a seat and enter their name
      seats.forEach((seat) => {
        seat.addEventListener("click", () => {
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
        if (handHasBeenDealt) {
          dealerSeatNumber = getSeatToRight(dealerSeatNumber);
        }
        
        renderRoleMarkers();
        communityCards.querySelector(".community-card-row").innerHTML = "";
        communityCards.classList.add("hidden");
        communityCards.classList.remove("is-visible");
        communityCards.style.display = "";
        askLoggedInSeatForBuyin();

        seats.forEach((seat) => {
          seat.querySelector(".seat-cards").innerHTML = "";
          seat.querySelectorAll(".raise-chip").forEach((chip) => chip.remove());
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
              raiserSeat = null;
              isCallRound = false;
              flopCards = Array.isArray(data.flop) ? data.flop : [];
              actionsRemaining = activeSeats.length;
              showCurrentPlayerActions();
              handHasBeenDealt = true;
            });
          })
          .catch(() => {
            window.alert("Could not connect to the deal endpoint.");
        });
      });

      function askLoggedInSeatForBuyin() {
        const loggedInSeat = document.querySelector(".seat-1");
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
        loggedInSeat.querySelector(".seat-buyin").textContent = cleanedBuyin
          ? `Buy-in: $${cleanedBuyin}`
          : "Buy-in: --";
        loggedInSeat.dataset.buyin = cleanedBuyin;
      }

      function createFaceUpCard(card) {
        const cardElement = document.createElement("span");
        cardElement.className = `mini-card mini-card-face ${card.color === "red" ? "mini-card-red" : "mini-card-black"}`;
        cardElement.innerHTML = `<span>${card.rank}</span><small>${card.suit}</small>`; 
        return cardElement;
      }

      function handlePlayerAction(button) {
        const action = button.dataset.action;
        const activeSeat = activeHandSeats[currentActionIndex];

        if (!activeSeat) {
          return;
        }

        if (action === "raise") {
          const playerName = activeSeat.querySelector("strong").textContent.trim();
          const raiseAmount = window.prompt(`How much does ${playerName} want to raise?`);
          
          if (raiseAmount === null) {
            return;
          }
          
          const cleanedRaiseAmount = raiseAmount.trim();

          if (cleanedRaiseAmount === "") {
            window.alert("Enter a raise first.");            
            return;
          }

          if (!subtractFromBuyin(activeSeat, cleanedRaiseAmount)) {
            return;
          }

          currentRaiseAmount = cleanedRaiseAmount;
          raiserSeat = activeSeat;
          isCallRound = true;
          actionsRemaining = activeHandSeats.length - 1;
          showRaiseChip(activeSeat, cleanedRaiseAmount);
          currentActionIndex = (currentActionIndex + 1) % activeHandSeats.length;
          showCurrentPlayerActions();
          return;
        } 
        
        if (action === "fold") {
          activeSeat.querySelector(".seat-cards").innerHTML = "";
          actionsRemaining -= 1;
          activeHandSeats.splice(currentActionIndex, 1);

          if (activeHandSeats.length <= 1) {
            hideAllSeatActions();
            window.alert("Hand is over.");
            return;
          }

          if (currentActionIndex >= activeHandSeats.length) {
            currentActionIndex = 0;
          }

          advancePreflopAction();
          return;
        } else if (action === "call") {
          if (!subtractFromBuyin(activeSeat, currentRaiseAmount)) {
            return;
          }

          actionsRemaining -= 1;
          currentActionIndex = (currentActionIndex + 1) % activeHandSeats.length;
          advancePreflopAction();
          return;
        } else {
          actionsRemaining -= 1;
          currentActionIndex = (currentActionIndex + 1) % activeHandSeats.length;
          advancePreflopAction();
          return;
        }
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

      function completePreflopAction() {
        hideAllSeatActions();
        isCallRound = false;

        if (activeHandSeats.length >= 2) {
          showFlopCards();
          return;
        }

        window.alert("Hand is over.");
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
      // Lines 278 to 316 generated by AI
      function showCurrentPlayerActions() {
        hideAllSeatActions();
        const activeSeat = activeHandSeats[currentActionIndex];

        if (!activeSeat) {
          return;
        }

        const seatActions = activeSeat.querySelector(".seat-actions");
        updateActionButtons(seatActions);
        seatActions.classList.remove("hidden");
        seatActions.hidden = false;
      }

      function updateActionButtons(seatActions) {
        const checkButton = seatActions.querySelector('[data-action="check"], [data-action="call"]');
        const raiseButton = seatActions.querySelector('[data-action="raise"]');

        if (isCallRound) {
          checkButton.textContent = `Call $${currentRaiseAmount}`;
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
        return true;
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

        for (let cardIndex = 0; cardIndex < cardsPerPlayer; cardIndex += 1) {
          activeSeats.forEach((seat, seatIndex) => {
            const card = deals[seatIndex]?.[cardIndex];

            if (!card) {
              return;
            }

            delay += dealDelay;
            dealSteps.push(new Promise((resolve) => {
              setTimeout(() => {
                seat.querySelector(".seat-cards").append(createFaceUpCard(card));
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
