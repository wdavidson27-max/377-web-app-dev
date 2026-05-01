<?php $seatCount = 8; ?>
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
      <section class="table-wrap" aria-label="Poker table">
        <div class="table-surface">
          <div class="table-rim"></div>

          <?php for ($seat = 1; $seat <= $seatCount; $seat++) : ?>
            <button
              type="button"
              class="seat seat-<?= $seat ?>"
              data-seat-number="<?= $seat ?>"
              data-default-name="Player <?= $seat ?>"
              data-player-active="false"
              aria-label="Seat <?= $seat ?>"
            >
              <span class="seat-tag">Seat <?= $seat ?></span>
              <strong>Player <?= $seat ?></strong>
              <span class="seat-cards" aria-live="polite"></span>
            </button>
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

          <div class="dealer-chip" aria-label="Dealer button">D</div>
        </div>
      </section>
    </main>


    
    <script>
      const dealEndpoint = "./deal.php";
      const dealButton = document.querySelector(".deal-button");
      const seats = document.querySelectorAll(".seat");

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
          seat.dataset.playerActive = cleanedName ? "true" : "false";
        });
      });

      dealButton.addEventListener("click", () => {
        const activeSeat = Array.from(seats).find((seat) => seat.dataset.playerActive === "true");

        if (!activeSeat) {
          window.alert("Add one player name to a seat before dealing.");
          return;
        }

        activeSeat.querySelector(".seat-cards").innerHTML = "";

        const formData = new FormData();
        formData.append("seat_number", activeSeat.dataset.seatNumber);

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

            activeSeat.querySelector(".seat-cards").append(
              createFaceDownCard(),
              createFaceDownCard()
            );
          })
          .catch(() => {
            window.alert("Could not connect to the deal endpoint.");
          });
      });

      function createFaceDownCard() {
        const cardElement = document.createElement("span");
        cardElement.className = "mini-card mini-card-back";
        cardElement.setAttribute("aria-hidden", "true");
        return cardElement;
      }
    </script>
  </body>
</html>
