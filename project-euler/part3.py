number = 600851475143
factor = 2

while factor * factor <= number:
    if number % factor == 0:
        number //= factor
    else:
        factor += 1

print(number)

