from sqlalchemy import Column, Integer, String, DateTime, Enum
from sqlalchemy.ext.declarative import declarative_base


Base = declarative_base()
metadata = Base.metadata


class CampaignTable(Base):                     # Model
    __tablename__ = 'campaign'

    id = Column(Integer, primary_key=True)
    company_id = Column(Integer)
    company_admin_id = Column(Integer)
    amount = Column(Integer)
    language = Column(Enum("English", "Bangla", name="language_enum"))
    campaign_name = Column(String)
    filename = Column(String)
    product = Column(String)
    message = Column(String)
    operator = Column(String)
    execution_time = Column(DateTime)
    interval = Column(Integer)
    status = Column(Enum("ongoing", "finished", 'cancelled', name="status_enum"))
    case = Column(Integer)
