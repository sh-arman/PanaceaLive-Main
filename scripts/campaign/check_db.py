import csv
# import re
import datetime
from zeep import Client
from sqlalchemy import create_engine, MetaData
from sqlalchemy.orm import sessionmaker
from models import CampaignTable


def send_sms(message, language, number=None, filename=None):
    url = 'https://api2.onnorokomsms.com/sendsms.asmx?WSDL'
    client = Client(url)

    userName = '01675430523'
    password = 'tapos99'
    smsText = message
    smsType = 'TEXT'
    if language == "Bangla":
        smsType = 'UCS'
    maskName = 'Panacea'
    campaignName = ''

    if filename:
        with open('../../public/consumerEngagement/campaign_files/' + filename, 'r') as infile:
            reader = csv.reader(infile)
            for row in reader:
                client.service.OneToOne(userName, password, row[0], smsText, smsType, maskName, campaignName)
    else:
        client.service.OneToOne(userName, password, number, smsText, smsType, maskName, campaignName)


engine = create_engine("mysql+pymysql://root:8080@localhost/panacea_schema?charset=utf8")

metadata = MetaData(engine)
Session = sessionmaker(bind=engine)
session = Session()

selected_rows = session.query(CampaignTable.filename, CampaignTable.message, CampaignTable.language, CampaignTable.execution_time).filter(CampaignTable.status == "ongoing")

for i in selected_rows:
    print(i.message)
    if (i.case == "1"):
        if ((i.execution_time <= datetime.datetime.now())):
            send_sms(i.message, i.language, filename=i.filename)
            i.campaign_status = "finished"
    elif (i.case == "2"):
        new_time = i.execution_time + datetime.timedelta(hours=i.interval)
        if (new_time <= datetime.datetime.now()):
            send_sms(i.message, i.language, filename=i.filename)
    elif (i.case == "3"):
        # needs to be reconsidered after the design is fixed
        if (i.target == "Maxpro, Rolac"):
            all_meds = "SELECT c1.phone_number, c1.created_at FROM check_history c1, code, print_order WHERE c1.id = (SELECT MAX(c2.id) FROM check_history c2 WHERE c1.phone_number = c2.phone_number AND c1.remarks=\"verified first time\" AND c2.remarks=\"verified first time\" ) AND c1.code=code.code AND code.status = print_order.id AND medicine_id = \"1\" ORDER BY c1.id DESC LIMIT " + i.amount + ";"
            for j in all_meds:
                # Get created_at of execution time
                new_time = j[0] + datetime.timedelta(hours=i.interval)
                if (new_time <= datetime.datetime.now()):
                    send_sms(i.message, i.language, number=j)
        elif (i.target == "Rolac"):
            # ROLAC = MED ID OF 1
                rolac = "SELECT c1.phone_number FROM check_history c1, code, print_order WHERE c1.id = (SELECT MAX(c2.id) FROM check_history c2 WHERE c1.phone_number = c2.phone_number AND c1.remarks=\"verified first time\" AND c2.remarks=\"verified first time\" ) AND c1.code=code.code AND code.status = print_order.id AND medicine_id = \"1\" ORDER BY c1.id DESC LIMIT " + i.amount + ";"
        elif (i.target == "Maxpro"):
            # MAXPRO = MED ID OF 3, 9, 10, 11
                maxpro = "SELECT c1.phone_number FROM check_history c1, code, print_order WHERE c1.id = (SELECT MAX(c2.id) FROM check_history c2 WHERE c1.phone_number = c2.phone_number AND c1.remarks=\"verified first time\" AND c2.remarks=\"verified first time\" ) AND c1.code=code.code AND code.status = print_order.id AND medicine_id  IN (3,9,10,11) ORDER BY c1.id DESC LIMIT " + i.amount + ";"

session.commit()
