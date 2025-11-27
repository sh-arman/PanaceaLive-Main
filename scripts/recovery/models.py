from sqlalchemy import Column, Integer, String
from sqlalchemy.ext.declarative import declarative_base


Base = declarative_base()
metadata = Base.metadata


class SampleCode(Base):                     # Model
    __tablename__ = 'code'

    id = Column(Integer, primary_key=True)
    code = Column(String(16))
    status = Column(Integer)
