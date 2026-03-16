a, b = 1, 2
total = 0

while a <= 4_000_000:
    if a % 2 == 0:
        total += a
    a, b = b, a + b

print(total)