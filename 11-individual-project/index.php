<?php
$seatCount = 8;
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
      <section class="table-wrap" aria-label="Poker table">
        <div class="table-surface">
          <div class="table-rim"></div>

          <?php for ($seat = 1; $seat <= $seatCount; $seat++) : ?>
            <button
              type="button"
              class="seat seat-<?= $seat ?>"
              data-seat-number="<?= $seat ?>"
              data-default-name="Player <?= $seat ?>"
              aria-label="Seat <?= $seat ?>"
            >
              <span class="seat-tag">Seat <?= $seat ?></span>
              <strong>Player <?= $seat ?></strong>
              <small class="seat-buyin">Buy-in: --</small>
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
      
      // Deal Button
      dealButton.style.left = "50%"
      dealButton.style.top = "67%";
      dealButton.style.transform = "translate(-50%, -40%)";
      dealButton.addEventListener("click", () => {
        seats.forEach((seat) => {
          seat.querySelector(".seat-cards").innerHTML = "";
          
        });

        const formData = new FormData();
        formData.append("player_count", seats.length);   
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

            seats.forEach((seat, seatIndex) => {
              const dealtCards = data.deals[seatIndex] || [];

              dealtCards.forEach((card) => {
                seat.querySelector(".seat-cards").append(createFaceUpCard(card));
              });
            });
          })
          .catch(() => {
            window.alert("Could not connect to the deal endpoint.");
          });
      });

      function createFaceUpCard(card) {
        const cardElement = document.createElement("span");
        cardElement.className = "mini-card mini-card-face";
        cardElement.innerHTML = `<span>${card.rank}</span><small>${card.suit}</small>`;
        return cardElement;
      }
    </script>
  </body>
</html>
