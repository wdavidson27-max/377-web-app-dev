import time


def check_factors(x, start, end):
    for i in range(start, end):
        if x % i != 0:
            return False
    return True    

def strategy1():
    number = 20

    while True:
        if check_factors(number, 2, 21):
            break

        number += 1
    return number    


print(strategy1)