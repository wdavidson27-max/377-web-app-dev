max = 0

for f1 in range(100, 1000):
    for f2 in range(100, 1000):
        product = f1 * f2
        candidate = str(product)
        if candidate == candidate[::-1] and product > max:
            max = product


print(max)            
    


