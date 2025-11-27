import csv
# import re
from sqlalchemy import create_engine, MetaData, Table, Column, Integer, String
from sqlalchemy.orm import sessionmaker
from models import SampleCode


engine = create_engine("mysql+pymysql://{username}:{password}@{hostname}/{databasename}".format(
    username="root",
    password="8080",
    hostname="localhost",
    databasename="panacea_schema",
), echo=False)

metadata = MetaData(engine)
Session = sessionmaker(bind=engine)
session = Session()
table = Table('code', metadata,
              Column('id', Integer, primary_key=True),
              Column('code', String(16)),
              Column('status', Integer))


table.create(checkfirst=True)

file_name = "Demo10k.csv"
regex = "(PBN [A-Z0-9]*)"

number_of_rows = input("Enter how many rows you want to select")
number_of_rows = int(number_of_rows)
status_of_code = input("Enter status of codes in this table")
status_of_code = int(status_of_code)
changed_status = input("Enter status you want to change it into")
changed_status = int(changed_status)
count = 0


# UPDATE
selected_rows = session.query(SampleCode).filter_by(status=status_of_code).limit(number_of_rows).all()


for i in selected_rows:
    i.status = changed_status

    with open("backup_sms.csv", "a") as outfile:
        writer = csv.writer(outfile)
        writer.writerow(["SMS (PBN " + i.code + ") to 2777 to VERIFY"])

session.commit()





"""
INSERT
with open(file_name, "r") as file:
    reader = csv.reader(file)
    for row in reader:
        count += 1
        print(count)
        code_ = re.search(regex, str(row)).group(0).split(" ")
        code_ = code_[1]

        load_values = table.insert().values(code=code, status=status_of_code)     # INSERT COMMAND!
        conn = engine.connect()
        conn.execute(load_values)
        # if (count == number_of_rows):
        #     break
"""
