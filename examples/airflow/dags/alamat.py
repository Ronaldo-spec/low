import docker


from airflow import DAG

from datetime import datetime, timedelta

from airflow.operators.python_operator import PythonOperator


default_args = {

    "owner": "airflow", 

    "start_date": datetime(2021, 3, 7),

    "retries": 1,

    "retry_delay": timedelta(minutes=5)}

    

def dim_tahun():

    client = docker.from_env()

    container = client.containers.get('d91523cdc77f26accd6407f787e257290de9f12c792f9a656af4712368eb5917')

    cmd = container.exec_run(

        "php artisan TakeTahunRental"

    
    )


def dim_lokasi():

    client = docker.from_env()

    container = client.containers.get('d91523cdc77f26accd6407f787e257290de9f12c792f9a656af4712368eb5917')

    cmd = container.exec_run(

        "php artisan TakeDimLokasi"

    
    )


def staging():

    client = docker.from_env()

    container = client.containers.get('d91523cdc77f26accd6407f787e257290de9f12c792f9a656af4712368eb5917')

    cmd = container.exec_run(

        "php artisan stagePelanggan"

    
    )


def faktaCust():

    client = docker.from_env()

    container = client.containers.get('d91523cdc77f26accd6407f787e257290de9f12c792f9a656af4712368eb5917')

    cmd = container.exec_run(

        "php artisan faktaCust"

    
    )


with DAG(

    dag_id="ETL-jadi", 

    default_args=default_args, 

    schedule_interval='@once'

    
    
) as dag:


    dim_tahun = PythonOperator(

        task_id="dim_tahun",

        python_callable=dim_tahun

    )


    dim_lokasi = PythonOperator(

        task_id="dim_lokasi",

        python_callable=dim_lokasi

    )

    staging = PythonOperator(

        task_id="staging",

        python_callable=staging

    )

    faktaCust = PythonOperator(

        task_id="fakta",

        python_callable=faktaCust

    )


    dim_tahun >> staging >> faktaCust

    dim_lokasi >> staging >> faktaCust
