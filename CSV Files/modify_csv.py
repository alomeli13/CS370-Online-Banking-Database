import csv
import random

# Lists for names
first_names = ['Alice', 'Bob', 'Charlie', 'Diana', 'Eve', 'Frank', 'Grace', 'Henry', 'Ivy', 'Jack', 'Kate', 'Liam', 'Mia', 'Noah', 'Olivia', 'Peter', 'Quinn', 'Ryan', 'Sophia', 'Tyler']
last_names = ['Adams', 'Baker', 'Clark', 'Davis', 'Evans', 'Foster', 'Garcia', 'Harris', 'Irwin', 'Jones', 'Kelly', 'Lewis', 'Miller', 'Nelson', 'Owens', 'Parker', 'Quinn', 'Roberts', 'Smith', 'Taylor']

def random_phone():
    return f"{random.randint(100,999)}-{random.randint(100,999)}-{random.randint(1000,9999)}"

def random_routing():
    return str(random.randint(100000000,999999999))

def random_salary():
    return str(random.randint(40000,100000))

with open('c:\\Users\\sedna\\PhpstormProjects\\CS370-Online-Banking-Database\\CSV Files\\updated_import3.csv', 'r') as f:
    reader = csv.reader(f)
    rows = list(reader)

header = rows[0]
new_rows = [header]

for row in rows[1:]:
    new_row = row.copy()
    # Randomly change some
    if random.choice([True, False]):
        new_row[5] = random_phone()  # PhoneNumber index 5
    if random.choice([True, False]):
        new_row[6] = random_routing()  # RoutingNumber 6
    # if random.choice([True, False]):
    #     new_row[11] = random.choice(first_names)  # Fname 11
    # if random.choice([True, False]):
    #     new_row[12] = random.choice(last_names)  # Lname 12
    if random.choice([True, False]):
        new_row[13] = random_salary()  # Salary 13
    new_rows.append(new_row)

with open('updated_import3.csv', 'w', newline='') as f:
    writer = csv.writer(f)
    writer.writerows(new_rows)