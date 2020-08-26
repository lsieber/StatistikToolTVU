'''
Created on 28.02.2020

@author: Lukas Sieber
'''
from src.sa.config.disziplins import getDisziplinByid
import statistics
from src.blv.Utils import formatResult
from src.blv.Utils import fromatResultOfPerformance

class KaderSheet():
    '''
    classdocs
    '''


    def __init__(self, name, limits):
        '''
        Constructor
        '''
        self.performancesByAthlete = {}
        self.otherLimitsByAthlete = {}
        self.athletes = {}
        self.name = name
        self.limits = limits
        self.disziplins = []
        self.disziplinIds = []
        self.extractDisziplinsForKader()
        
        
        
    def extractDisziplinsForKader(self):
        for limit in self.limits.limitsClasses:
            if self.name == limit.kader:
                self.disziplins.append(limit.disziplin)
                self.disziplinIds.append(limit.disziplin.id)

            
    def addPerformance(self, performance):
        if performance.disziplin.id in self.disziplinIds:
            if performance.athlete.id not in self.performancesByAthlete:
                self.performancesByAthlete[performance.athlete.id] = {}
            if performance.disziplin.id not in self.performancesByAthlete[performance.athlete.id]:
                self.performancesByAthlete[performance.athlete.id][performance.disziplin.id] = []
            self.performancesByAthlete[performance.athlete.id][performance.disziplin.id].append(performance)
            
            if self.limits.performanceFulfillsOneLimit(performance):
                self.athletes[performance.athlete.id] = performance.athlete

        else:
            if self.limits.performanceFulfillsOneLimit(performance):
                if performance.athlete.id not in self.otherLimitsByAthlete:
                    self.otherLimitsByAthlete[performance.athlete.id] = {}
                if performance.disziplin.id not in self.otherLimitsByAthlete[performance.athlete.id]:
                    self.otherLimitsByAthlete[performance.athlete.id][performance.disziplin.id] = []
                self.otherLimitsByAthlete[performance.athlete.id][performance.disziplin.id].append(performance)
                
              
    def exportSheet(self, exportfile):
        with open(exportfile, 'w') as f:
            f.write("%s Kader\n" % self.name)
            f.write("%s;%s;%s;%s;%s;%s;%s;%s;%s;%s\n" % ("Name", "Jg", "Geschl.", "Disziplin", "SB", "Avg best 3", "Anzahl Resultate", "Limite", "Limite erreicht", "andere Limiten"))

            for athleteId in self.athletes:
                firstRowAthlete = True
                athlete = self.athletes[athleteId]
                otherLimits = []
                otherLimitString = ""
                if athlete.id in self.otherLimitsByAthlete:
                    for disziplinId in self.otherLimitsByAthlete[athlete.id]:
                        performances = self.otherLimitsByAthlete[athlete.id][disziplinId]
                        performances.sort(key=lambda p: float(p.result), reverse = not getDisziplinByid(disziplinId).ascending)
                        otherLimits.append(performances[0])
                        otherLimitString += performances[0].disziplin.name + ": " + str(performances[0].result) + " / "

                for disziplinId in self.performancesByAthlete[athleteId]:
                    disziplin = getDisziplinByid(disziplinId)
                    performances = self.performancesByAthlete[athleteId][disziplinId]
                    
                    performances.sort(key=lambda p: float(p.result), reverse = not disziplin.ascending)
                    limit = self.limits.getLimitByPerformance(performances[0])
                    limitValue = ("%.2f" % limit.value) if limit != None else "keine"
                    fullfillsLimit = "erfüllt" if self.limits.performanceFulfillsOneLimit(performances[0]) else ""
                    
                    bestResult = fromatResultOfPerformance(performances[0])
                    best3average = formatResult(statistics.mean(float(p.result) for p in performances[:3]), disziplin) if len(performances) > 3 else ""
                    if firstRowAthlete:
                        f.write("%s;%s;%s;%s;%s;%s;%i;%s;%s;%s\n" % (athlete.name, athlete.birthYear, athlete.gender, disziplin.name, bestResult, best3average, len(performances), limitValue, fullfillsLimit, otherLimitString))
                        firstRowAthlete = False
                    else:
                        f.write(";;;%s;%s;%s;%i;%s;%s\n" % (disziplin.name, bestResult, best3average, len(performances), limitValue, fullfillsLimit))
                    
                    
        print("exported file: " + exportfile)
        