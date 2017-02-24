import MySQLdb
import urllib
import sys
import urlparse
import string
import re
from bs4 import BeautifulSoup

page=[]

def links(urlz):
    for pg in urlz:        #pg- single url   #urlz-list of urls
        if pg not in page:  
            page.append(pg)
            #pages.append(title+"---"+pg) 
            #print pg,"appended"
            print"cur length: ",len(page)
            pk=[]
            pk=getsublinks(pg)
            if pk!= []:
                try: 
                    for one in pk:
                        if one in page:
                            pk.remove(one)
                except :
                    e = sys.exc_info()[0]
                    print"\nException",e
                    print pk
                    sys.exit()
                links(pk)    
   

def write_db(pag):
    print("into write DB")
    # CONNECTION
    try:
            db = MySQLdb.connect("localhost","root","password","dbname" )
            cursor = db.cursor()
    except:
            print("DB connection error")
            sys.exit()     
    # CONNECTION
    for url in pag:               
        title,words = get_words(url)
        print words,"\n"  
        t=title     
        u=url
        sql="INSERT IGNORE INTO `fetchdb`.`urls` (`url`, `title`) VALUES (%s,%s)" 
        args=(u,t)
        print u,"- writing URLs to DB"
        try:
            cursor.execute(sql,args)
            db.commit()
            url_id=cursor.lastrowid
        except  MySQLdb.Error, e:
            print "URL DB write failed...",u,"--",e
            db.rollback()
            pass
# If words list is not empty
        if words:        
# Insert Unique words
            for w in words:
                arg=(w)
                sql2="Insert INTO `fetchdb`.`words`(`word`) VALUES (%s)"
                print (w,"writing word to DB")
                try:
                    cursor.execute(sql2,[arg])
                    db.commit()
                    word_id=cursor.lastrowid
                except  MySQLdb.Error, e:
                    print "DB write failed...",w,"--",e
                    db.rollback()
                    sys.exit()     
# Match Ids
                i=int(word_id)
                j=int(url_id)
                sql3="INSERT INTO `word_url`(`word_id`, `url_id`) VALUES (%d,%d)" %(i,j)
                try:
                    cursor.execute(sql3)
                    db.commit()
                except  MySQLdb.Error, e:
                    print "word_url write failed",e
                    db.rollback()
                    sys.exit()
        else:
            print "No words at",url
    cursor.close()   
    db.close()
    
    

def write_file(pag):
    i=0
    path="F:\My projects\list.txt"
    f= open(path,'w')
    for l in pag:
            f.write(l)
            f.write("\n")

        




def get_words(url):
    try: 
            htmlf=urllib.urlopen(url)
    except:
            print"can't open",url
            sys.exit()
    text=htmlf.read()
    l=[]
    word_list=[]
    soup=BeautifulSoup(text,"html.parser")
    soup.encode('utf-8')
    title=soup.title.string.encode('utf-8')
    common_words = {'a','all', 'just', 'being', 'over', 'both','through', 'yourselves', 'its', 'before', 'herself', 'had', 'should', 
           'to', 'only', 'under', 'ours', 'has','do', 'them', 'his', 'very', 'they', 'not', 'during', 'now', 'him', 'nor', 'did', 
           'this', 'she', 'each', 'further','yes','no','ok','must',
            'few', 'because', 'doing', 'some', 'are', 'our', 'ourselves', 'out', 'what', 'does', 'above',
            'between', 'be', 'we', 'who', 'were', 'here', 'hers', 'by', 'on', 'about', 'of', 'against', 's', 'or', 'own', 'into',
             'yourself', 'down', 'your', 'from', 'her', 'their', 'there', 'been', 'whom', 'too', 'themselves', 'was', 'until', 'more',
              'himself', 'that', 'but', 'don\'t', 'with', 'than', 'those', 'he', 'me', 'myself', 'these', 'up', 'will', 'below', 'can', 'theirs',
               'my', 'and', 'then', 'is', 'am', 'it', 'an', 'as', 'itself', 'at', 'have', 'in', 'any', 'again', 'no', 'when', 'same', 
              'how', 'other', 'which', 'you', 'after', 'most', 'such', 'why', 'off', 'i', 'yours', 'so', 'the', 'having', 'once'}
    
    soup.get_text()
    [s.extract() for s in soup('code')]
    [s.extract() for s in soup('a')]
    reo=re.compile("[a-z]+$")
    tex=soup.getText().encode('utf-8').lower()
    for t in tex.split():
        replace_punctuation = string.maketrans("\"()\'\.\?<>=,--:[]", ' '*len("\"()\'\.\?<>=,--:[]"))
        t = t.translate(replace_punctuation)
        t=t.strip()
        if len(t) > 1:
            if re.match(reo,t): 
                l.append(t)
    k = set(l)
    for li in k:
        if li not in common_words:
            word_list.append(li)
    return title,word_list

def getsublinks(url): 
#   print"---> sublinks of ",url 
    url_l=[]
    try:
        htmlfile=urllib.urlopen(url)
        htmltext=htmlfile.read()
        soup=BeautifulSoup(htmltext,"html.parser")
    except :
        print "url not found -",url
        sys.exit()
    for gg in soup.find_all('div',id = True):
        ph4=gg.find_all('a',href=True)
        for ph in ph4:
            if "http://" not in ph['href']:
                if "ftp:" not in ph['href']:
                    if "#" not in ph['href']:
                        if ".html" in ph['href']: 
                            ull=urlparse.urljoin(url,ph['href'])
                            full=str(ull)
                            if full not in url_l:
                                url_l.append(full)
    return url_l



root = "http://localhost/nextbigthing/phpmanual/index.html"

urls=getsublinks(root)

if urls!=[]: 
    links(urls)  
else:
    sys.exit()
  
print("\n\n----------------:)----------------\n\n")  
print("Length page:",len(page))

write_file(page)
 #write_db(page)   
print("Check DB")


