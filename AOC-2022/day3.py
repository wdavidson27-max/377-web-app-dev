file = open('day3.txt', 'r')
lines = file.readlines()

alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"


total = 0

for line in lines:
    first_half = line[:len(line) // 2]

    second_half = line[len(line) // 2:]

    print(line)
    print(first_half)
    print(second_half)

    for letter in first_half:
        if letter in second_half:
            total += int(alphabet.index(letter) + 1)
            print("Found common letter " + letter)
            print(alphabet.index(letter) + 1)
            break
print(total)

r1 = "vJrwpWtwJgWrhcsFMMfFFhFp"
r2 = "jqHRNqRjqzjGDLGLrsFMfFZSrLrFZsSL"
r3 = "PmmdzqPrVvPwwTWBwg"

for letter in r1:
    if letter in r2 and letter in r3:
        print("Found it " + letter)
        break