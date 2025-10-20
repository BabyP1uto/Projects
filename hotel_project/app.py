# app.py
from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from datetime import datetime, date
from marshmallow import Schema, fields, ValidationError, validates, validate
import re
import os

BASE_DIR = os.path.abspath(os.path.dirname(__file__))
DB_PATH = "sqlite:///" + os.path.join(BASE_DIR, "app.db")

app = Flask(__name__)
app.config["SQLALCHEMY_DATABASE_URI"] = DB_PATH
app.config["SQLALCHEMY_TRACK_MODIFICATIONS"] = False

db = SQLAlchemy(app)

# ----------------
# MODELS
# ----------------

class RoomType(db.Model):
    __tablename__ = "room_types"
    id = db.Column(db.Integer, primary_key=True)
    type_name = db.Column(db.String(50), nullable=False)
    description = db.Column(db.Text)
    base_price = db.Column(db.Float, nullable=False)
    max_guests = db.Column(db.Integer, nullable=False, default=2)

class Room(db.Model):
    __tablename__ = "rooms"
    id = db.Column(db.Integer, primary_key=True)
    room_number = db.Column(db.String(20), nullable=False, unique=True)
    room_type_id = db.Column(db.Integer, db.ForeignKey("room_types.id"), nullable=False)
    status = db.Column(db.String(20), nullable=False, default="Available")  # Available / Occupied / Maintenance
    floor = db.Column(db.Integer)
    room_type = db.relationship("RoomType")

class Guest(db.Model):
    __tablename__ = "guests"
    id = db.Column(db.Integer, primary_key=True)
    first_name = db.Column(db.String(50), nullable=False)
    last_name = db.Column(db.String(50), nullable=False)
    passport_number = db.Column(db.String(20), nullable=False, unique=True)
    phone_number = db.Column(db.String(20), nullable=False)
    email = db.Column(db.String(120))

class Reservation(db.Model):
    __tablename__ = "reservations"
    id = db.Column(db.Integer, primary_key=True)
    reservation_number = db.Column(db.String(50), nullable=False, unique=True)
    guest_id = db.Column(db.Integer, db.ForeignKey("guests.id"), nullable=False)
    room_id = db.Column(db.Integer, db.ForeignKey("rooms.id"), nullable=True)
    planned_checkin = db.Column(db.Date, nullable=False)
    planned_checkout = db.Column(db.Date, nullable=False)
    status = db.Column(db.String(20), nullable=False, default="Booked")  # Booked, CheckedIn, Cancelled
    total_price = db.Column(db.Float)
    payment_method = db.Column(db.String(30))
    guest = db.relationship("Guest")
    room = db.relationship("Room")

# ----------------
# Validation Schemas (Marshmallow)
# ----------------

def validate_phone(value):
    if not re.fullmatch(r"\+7\d{10}", value):
        raise ValidationError("Phone must be in format +7XXXXXXXXXX")

def validate_passport(value):
    if not re.fullmatch(r"\d{10}", value):
        raise ValidationError("Passport must contain exactly 10 digits")

class GuestSchema(Schema):
    first_name = fields.Str(required=True, validate=validate.Length(1, 50))
    last_name = fields.Str(required=True, validate=validate.Length(1, 50))
    passport_number = fields.Str(required=True, validate=validate_passport)
    phone_number = fields.Str(required=True, validate=validate_phone)
    email = fields.Email(required=False, allow_none=True)

class ReservationSchema(Schema):
    reservation_number = fields.Str(required=True, validate=validate.Length(1, 50))
    guest = fields.Nested(GuestSchema, required=True)
    room_id = fields.Int(required=False, allow_none=True)
    planned_checkin = fields.Date(required=True)
    planned_checkout = fields.Date(required=True)
    total_price = fields.Float(required=False, allow_none=True, validate=validate.Range(min=0))
    payment_method = fields.Str(required=False, validate=validate.OneOf(["card", "cash", "transfer"]))

    @validates("planned_checkin")
    def check_checkin(self, value):
        if value < date.today():
            raise ValidationError("Check-in date cannot be in the past")

    @validates("planned_checkout")
    def check_checkout(self, value):
        if value < date.today():
            raise ValidationError("Check-out date cannot be in the past")

# ----------------
# Marshmallow error handler
# ----------------
def handle_marshmallow_errors(fn):
    def wrapper(*args, **kwargs):
        try:
            return fn(*args, **kwargs)
        except ValidationError as ve:
            return jsonify({"error": ve.messages}), 400
    wrapper.__name__ = fn.__name__
    return wrapper

# ----------------
# Routes
# ----------------

@app.route("/api/rooms", methods=["GET"])
def list_rooms():
    rooms = Room.query.all()
    res = []
    for r in rooms:
        res.append({
            "id": r.id,
            "room_number": r.room_number,
            "room_type": r.room_type.type_name if r.room_type else None,
            "status": r.status,
            "floor": r.floor
        })
    return jsonify(res), 200

@app.route("/api/reservations", methods=["GET"])
def list_reservations():
    rs = Reservation.query.all()
    out = []
    for r in rs:
        out.append({
            "id": r.id,
            "reservation_number": r.reservation_number,
            "guest": f"{r.guest.first_name} {r.guest.last_name}",
            "room_id": r.room_id,
            "checkin": r.planned_checkin.isoformat(),
            "checkout": r.planned_checkout.isoformat(),
            "status": r.status,
            "total_price": r.total_price
        })
    return jsonify(out), 200

@app.route("/api/book", methods=["POST"])
@handle_marshmallow_errors
def create_reservation():
    json_data = request.get_json()
    if not json_data:
        return jsonify({"error": "No JSON provided"}), 400

    schema = ReservationSchema()
    data = schema.load(json_data)

    if data["planned_checkout"] <= data["planned_checkin"]:
        return jsonify({"error": "Check-out date must be after check-in"}), 400

    guest_data = data["guest"]
    guest = Guest.query.filter_by(passport_number=guest_data["passport_number"]).first()
    if not guest:
        guest = Guest(
            first_name=guest_data["first_name"],
            last_name=guest_data["last_name"],
            passport_number=guest_data["passport_number"],
            phone_number=guest_data["phone_number"],
            email=guest_data.get("email")
        )
        db.session.add(guest)
        db.session.flush()

    room_id = data.get("room_id")
    assigned_room = None
    if room_id:
        assigned_room = Room.query.filter_by(id=room_id).first()
        if not assigned_room:
            return jsonify({"error": "Specified room not found"}), 400
        if assigned_room.status != "Available":
            return jsonify({"error": "Room is not available"}), 400

    reservation = Reservation(
        reservation_number=data["reservation_number"],
        guest_id=guest.id,
        room_id=assigned_room.id if assigned_room else None,
        planned_checkin=data["planned_checkin"],
        planned_checkout=data["planned_checkout"],
        status="Booked",
        total_price=data.get("total_price"),
        payment_method=data.get("payment_method")
    )

    if assigned_room:
        assigned_room.status = "Occupied"

    db.session.add(reservation)
    db.session.commit()

    return jsonify({"message": "Reservation created successfully", "reservation_id": reservation.id}), 201

@app.route("/api/guests/<passport>", methods=["GET"])
def get_guest_by_passport(passport):
    guest = Guest.query.filter_by(passport_number=passport).first()
    if not guest:
        return jsonify({"error": "Guest not found"}), 404
    return jsonify({
        "id": guest.id,
        "first_name": guest.first_name,
        "last_name": guest.last_name,
        "passport_number": guest.passport_number,
        "phone_number": guest.phone_number,
        "email": guest.email
    }), 200

@app.route("/init-demo", methods=["POST"])
def init_demo():
    if RoomType.query.count() == 0:
        rt1 = RoomType(type_name="Standard", description="Standard room", base_price=3000.0, max_guests=2)
        rt2 = RoomType(type_name="Business", description="Business class", base_price=5000.0, max_guests=2)
        db.session.add_all([rt1, rt2])
        db.session.flush()
        r1 = Room(room_number="101", room_type_id=rt1.id, status="Available", floor=1)
        r2 = Room(room_number="102", room_type_id=rt2.id, status="Available", floor=1)
        db.session.add_all([r1, r2])
        db.session.commit()
        return jsonify({"message": "Demo data created"}), 201
    else:
        return jsonify({"message": "Demo data already exists"}), 200

# ----------------
# Удаление демо-данных
# ----------------
@app.route("/delete-demo", methods=["POST"])
def delete_demo():
    with app.app_context():
        # Удаляем бронирования
        Reservation.query.delete()
        # Удаляем гостей
        Guest.query.delete()
        # Удаляем комнаты
        Room.query.delete()
        # Удаляем типы комнат
        RoomType.query.delete()
        db.session.commit()
    return jsonify({"message": "Demo data deleted"}), 200

# ----------------
# DB Initialization
# ----------------
def init_db():
    with app.app_context():
        db.create_all()
        print("Database initialized")

if __name__ == "__main__":
    init_db()
    app.run(host="127.0.0.1", port=5000, debug=True)
