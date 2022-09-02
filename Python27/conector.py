url="http://localhost:8069"
db = "naida"
username = "admin"
password = "admin"
nb_caja="Caja 2/"

import xmlrpc.client
import mysql.connector
from mysql.connector import errorcode
import time
#import ssl
#ssl._create_default_https_context=ssl._creat_unverified_context
##import requests
##requests.get('https://igneisker2012.odoo.com', cert='/path/server.crt', verify=False)
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
if mydb.is_connected():
    pass
    #print("conexion exitosa")
#time.sleep(6)
#---------------- FUNCIONES ----------------------------------------------------------------
def nb_producto(id_product):
    list_product=models.execute_kw(db, uid, password,
        'product.product', 'search_read',
        [[['id', '=',id_product]]],
        {'fields': ['product_tmpl_id']})
    for det_tmp in list_product:
        id_template=det_tmp['product_tmpl_id'][0]
        list_template=models.execute_kw(db, uid, password,
        'product.template', 'search_read',
        [[['id', '=',id_template]]],
        {'fields': ['name']})
        for det_nb in list_template:
            nombre=det_nb['name']
    return nombre

def nb_partner(id_partner):
    nombre='Generico'
    if id_partner:
        list_partner=models.execute_kw(db, uid, password,
            'res.partner', 'search_read',
            [[['id', '=',id_partner]]],
            {'fields': ['id','name'], 'limit': 1})
        for cet in list_partner:
            nombre=cet['name']
    return nombre

def cedula(id_partner):
    cedula='000000'
    if id_partner:
        list_partner=models.execute_kw(db, uid, password,
            'res.partner', 'search_read',
            [[['id', '=',id_partner]]],
            {'fields': ['id','vat'], 'limit': 1})
        for cett in list_partner:
            if cett['vat']:
                #print(cett['vat'])
                cedula=cett['vat']
    return cedula

def telefono(id_partner):
    telefono='-------'
    if id_partner:
        list_partner=models.execute_kw(db, uid, password,
            'res.partner', 'search_read',
            [[['id', '=',id_partner]]],
            {'fields': ['id','phone'], 'limit': 1})
        for cett in list_partner:
            if cett['phone']:
                #print(cett['vat'])
                telefono=cett['phone']
    return telefono

def direccion(id_partner):
    direccion='Valencia'
    if id_partner:
        list_partner=models.execute_kw(db, uid, password,
            'res.partner', 'search_read',
            [[['id', '=',id_partner]]],
            {'fields': ['id','street'], 'limit': 1})
        for cettt in list_partner:
            print(cettt['street'])
            if cettt['street']:
                direccion=cettt['street']
    return direccion

def nb_user(id_users):
    list_partner=models.execute_kw(db, uid, password,
        'res.users', 'search_read',
        [[['id', '=',id_users]]],
        {'fields': ['id','login'], 'limit': 1})
    for tet in list_partner:
        nombre=tet['login']
    return nombre

def nb_company(id_company):
    list_partner=models.execute_kw(db, uid, password,
        'res.company', 'search_read',
        [[['id', '=',id_company]]],
        {'fields': ['id','name'], 'limit': 1})
    for tet in list_partner:
        nombres=tet['name']
    return nombres

def tipo_doc(id_product):
    producto_imp="no"
    list_product=models.execute_kw(db, uid, password,
        'product.product', 'search_read',
        [[['id', '=',id_product]]],
        {'fields': ['product_tmpl_id'], 'limit': 1})
    for det_tmp in list_product:
        id_template=det_tmp['product_tmpl_id'][0]
        list_template=models.execute_kw(db, uid, password,
            'product.template', 'search_read',
            [[['id', '=',id_template]]],
            {'fields': ['taxes_id'], 'limit': 1})
        for det_nb in list_template:
            if det_nb['taxes_id']:
                id_tax=det_nb['taxes_id'][0]
                producto_imp="si"
    if producto_imp=="si":
        lista_tax=models.execute_kw(db, uid, password,
            'account.tax', 'search_read',
            [[['id', '=',id_tax]]],
            {'fields': ['tipo_tasa','amount'], 'limit': 1})
    else:
        lista_tax=models.execute_kw(db, uid, password,
            'account.tax', 'search_read',
            [[['aliquot', '=','exempt']]],
            {'fields': ['tipo_tasa','amount'], 'limit': 1})
    for det_tax in lista_tax:
        tipo_tasa=det_tax['tipo_tasa']
        monto=det_tax['amount']
    lista={'valor':tipo_tasa,'amount':monto}
    return lista
#---------------------------------------------------------------------------------

order=models.execute_kw(db, uid, password,
    'pos.order', 'search_read',
    [[['name', 'like',nb_caja],['amount_total', '>','0']]],
    {'fields': ['id','name','partner_id','user_id','state','nb_caja','pos_reference','company_id','tasa_dia'],'offset': 0, 'limit': 1})

print(order)
for det in order:
    pos_order_id=det['id']
    pos_reference=det['pos_reference']
    #print(det['nb_caja'])
    if not det['partner_id']:
        cliente="Generico"
        cedula="V00000000"
        direccion=" "
    else:
        cliente=nb_partner(det['partner_id'][0])
        #cedula="00000000"
        cedula=cedula(det['partner_id'][0])
        direccion=direccion(det['partner_id'][0]) #"Valencia"
        telefono=telefono(det['partner_id'][0])
    usuario=nb_user(det['user_id'][0])
    compania=nb_company(det['company_id'][0])
    state=det['state']
    nb_caja=det['nb_caja']
    tasa=det['tasa_dia']
    tasa=round(tasa,2)  
    #print(tasa)
    mycursor = mydb.cursor()
    sql = "INSERT INTO pos_order (order_id,cliente,usuario,state,nb_caja,cedula,direccion,pos_reference,compania,tasa_dia,telefono) VALUES ('{0}','{1}','{2}','{3}','{4}','{5}','{6}','{7}','{8}','{9}','{10}')".format(pos_order_id,cliente,usuario,state,nb_caja,cedula,direccion,pos_reference,compania,tasa,telefono)
    mycursor.execute(sql)
    #mydb.commit()
    order_line=models.execute_kw(db, uid, password,
        'pos.order.line', 'search_read',
        [[['order_id', '=',pos_order_id]]],
        {'fields': ['id','name','price_unit','qty','product_id','order_id']})
    nb_articulo=id_line_order=id_order=precio_unit=cantidad=valor=valor_alicuota=0
    for det_line in order_line:
        id_line_order=det_line['id']
        cantidad=det_line['qty']
        precio_unit=det_line['price_unit']#*tasa  # aqui solo es para isneiker
        ##producto=det_line['product_id']
        #print(id_line_order)
        id_order=det_line['order_id'][0]
        #print(id_order)
        #print(precio_unit)
        nb_articulo=nb_producto(det_line['product_id'][0])
        #print(nb_articulo)
        #print(cantidad)
        dic_lista=tipo_doc(det_line['product_id'][0])
        #print(dic_lista)
        valor=dic_lista['valor']
        valor_alicuota=dic_lista['amount']      
        #mycursor = mydb.cursor()
        sql = "INSERT INTO pos_order_line (producto,line_order_id,order_id,price_unit,cantidad,tipo_doc,valor_alicuota) VALUES ('{0}','{1}','{2}','{3}','{4}','{5}','{6}')".format(nb_articulo,id_line_order,id_order,precio_unit,cantidad,valor,valor_alicuota)
        mycursor.execute(sql)
        mydb.commit()
        #mycursor.execute(sql, (nb_articulo))

"""order_update=models.execute_kw(db, uid, password, 'pos.order', 'write', [pos_order_id, {
    'status_impresora': "si"
}])"""