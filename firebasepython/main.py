import json
import os
from typing import Literal, Optional
from uuid import uuid4
from fastapi import FastAPI, HTTPException
import random
from fastapi.encoders import jsonable_encoder
from pydantic import BaseModel
from mangum import Mangum
from firebaseaccesstoken import FirebaseManager
import requests

class Book(BaseModel):
    name: str
    genre: Literal["fiction", "non-fiction"]
    price: float
    book_id: Optional[str] = uuid4().hex

class NotificationRequest(BaseModel):
    device_token: str
    notification_title: str
    notification_body: str
    image_url: str = None
    data: dict = None


BOOKS_FILE = "books.json"
BOOKS = []

if os.path.exists(BOOKS_FILE):
    with open(BOOKS_FILE, "r") as f:
        BOOKS = json.load(f)

app = FastAPI()
handler = Mangum(app)


@app.get("/")
async def root():
    return {"message": "Welcome to my bookstore app!"}


@app.get("/random-book")
async def random_book():
    return random.choice(BOOKS)


@app.get("/access_token")
async def list_books():
    obj = FirebaseManager()
    return obj.getAceesToken()


@app.post("/postAndroidNotification")
async def postAndroidNotification(request: NotificationRequest):
    obj = FirebaseManager()
    token = obj.getAceesToken()
    return send_notification_to_android(token, request.device_token, request.notification_title, request.notification_body, request.image_url, request.data)

def send_notification_to_android(token, device_token, notification_title, notification_body, image_url=None, data=None):
    # Construct the notification payload
   
    url = f"https://fcm.googleapis.com/v1/projects/easyride-417501/messages:send"
    headers = {
        "Authorization": f"Bearer {token}",
        "Content-Type": "application/json"
    }
    data = {
        "message": {
            "token": device_token,
            "notification": {
                "title": notification_title,
                "body": notification_body,
                "image": image_url
            },
            "data": data
        }
    }

    response = requests.post(url, headers=headers, json=data)

    if response.status_code == 200:
        return {"status": "Notification sent successfully"}
    else:
        return {"status": "Failed to send notification. Status code: {response.status_code}"}



@app.get("/book_by_index/{index}")
async def book_by_index(index: int):
    if index < len(BOOKS):
        return BOOKS[index]
    else:
        raise HTTPException(404, f"Book index {index} out of range ({len(BOOKS)}).")


@app.post("/add-book")
async def add_book(book: Book):
    book.book_id = uuid4().hex
    json_book = jsonable_encoder(book)
    BOOKS.append(json_book)

    with open(BOOKS_FILE, "w") as f:
        json.dump(BOOKS, f)

    return {"book_id": book.book_id}


@app.get("/get-book")
async def get_book(book_id: str):
    for book in BOOKS:
        if book.book_id == book_id:
            return book

    raise HTTPException(404, f"Book ID {book_id} not found in database.")
