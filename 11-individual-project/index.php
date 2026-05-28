<?php
require_once __DIR__ . '/session.php';

start_app_session();

$seatCount = 8;
$isLoggedIn = isset($_SESSION['user_id']);
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
    <link rel="stylesheet" href="./styles.css" />
  </head>
  <body>
    <main class="table-scene">
      <section class="table-header-bar" aria-label="User session">
        <?php if ($isLoggedIn): ?>
          <span class="login-status">Logged In</span>
        <?php else: ?>
          <a class="login-link" href="./login.php">Log In</a>
        <?php endif; ?>
      </section>
      <section class="table-wrap" aria-label="Poker table">
        <div class="table-surface">
          <div class="table-rim"></div>

          <?php for ($seat = 1; $seat <= $seatCount; $seat++) : ?>
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
              <strong>Player <?= $seat ?></strong>
              <small class="seat-buyin">Buy-in: --</small>
              <span class="seat-cards" aria-live="polite"></span>
              <span class="seat-actions hidden" aria-label="Preflop actions" hidden>
                <button type="button" class="seat-action-button">Check</button>
                <button type="button" class="seat-action-button">Raise</button>
                <button type="button" class="seat-action-button">Fold</button>
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
      const seats = document.querySelectorAll(".seat");
      let dealerSeatNumber = 1;
      let handHasBeenDealt = false;


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
        });
      });

      // Deal Button
      dealButton.addEventListener("click", () => {
        if (handHasBeenDealt) {
          dealerSeatNumber = getSeatToRight(dealerSeatNumber);
        }
        
        renderRoleMarkers();

        seats.forEach((seat) => {
          seat.querySelector(".seat-cards").innerHTML = "";
          const seatActions = seat.querySelector(".seat-actions");
          seatActions.classList.add("hidden");
          seatActions.hidden = true;
          
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
              const firstPlayerActions = activeSeats[0].querySelector(".seat-actions");
              firstPlayerActions.classList.remove("hidden");
              firstPlayerActions.hidden = false;
              handHasBeenDealt = true;
            });
          })
          .catch(() => {
            window.alert("Could not connect to the deal endpoint.");
          });
      });

      function createFaceUpCard(card) {
        const cardElement = document.createElement("span");
        cardElement.className = `mini-card mini-card face ${card.color === "red" ? "mini-card-red" : "mini-card-black"}`;
        cardElement.innerHTML = `<span>${card.rank}</span><small>${card.suit}</small>`;
        return cardElement;
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
