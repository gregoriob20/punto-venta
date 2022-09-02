url="http://localhost:8069"
db = "naida"
username = "admin"
password = "admin"
nb_caja="Caja 2/"


import xmlrpc.client
import mysql.connector
from mysql.connector import errorcode
"""info = xmlrpc.client.ServerProxy('https://demo.odoo.com/start').start()
#print(info)
url, db, username, password = \
    info['host'], info['database'], info['user'], info['password']"""

common = xmlrpc.client.ServerProxy('{}/xmlrpc/2/common'.format(url))
common.version()
{
    "server_version": "13.0",
    "server_version_info": [13, 0, 0, "final", 0],
    "server_serie": "13.0",
    "protocol_version": 1,
}
uid = common.authenticate(db, username, password, {})

models = xmlrpc.client.ServerProxy('{}/xmlrpc/2/object'.format(url))
"""models.execute_kw(db, uid, password,
    'res.partner', 'check_access_rights',
    ['read'], {'raise_exception': False})"""

mydb = mysql.connector.connect(host='localhost', port=3306, user='root', password='', db='odoo')

mycursor = mydb.cursor()
sql = "SELECT nc_id,pos_order_id FROM pos_secuencia_nc ORDER BY nc_id DESC"
mycursor.execute(sql)
resultado=mycursor.fetchone()
nc_id=resultado[0]
pos_order_id=resultado[1]
print(pos_order_id)
order_update=models.execute_kw(db, uid, password, 'pos.order', 'write', [pos_order_id, {
    'nro_nc_seniat': nc_id
}])
