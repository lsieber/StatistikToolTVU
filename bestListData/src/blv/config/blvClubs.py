
from src.blv.elmt.Club import Club
def getClubIds():
    return (x.id for x in getClubs())
    
def getClubNames():
    return (x.name for x in getClubs())

def getClubByid(clubId):
    return next((x for x in getClubs() if x.id == clubId), None)

def getClubs():
    return [
        Club("ACC_1.BE.0159", "TV Unterseen"),
        Club("ACC_1.BE.0154", "LV Thun")
        ]


if __name__ == '__main__':
    clubs = getClubs()
    print(clubs[0].name)