from flask import Flask, render_template, request, redirect, url_for, session, jsonify
import sqlite3

app = Flask(__name__)
app.secret_key = 'your_very_secret_key' # Replace with a real secret key

# Function to get a database connection.
def get_db_connection():
    conn = sqlite3.connect('database.db')
    conn.row_factory = sqlite3.Row
    return conn

@app.route('/')
def home():
    return "<h1>Welcome to the CineCraze Backend</h1><p>This is the placeholder for the API and admin panel.</p>"

@app.route('/admin')
def admin_redirect():
    return redirect(url_for('admin_login'))

@app.route('/admin/login', methods=['GET', 'POST'])
def admin_login():
    # Will implement login logic later
    return "<h2>Admin Login Page</h2>"

@app.route('/admin/dashboard')
def admin_dashboard():
    # Will be a protected route
    return "<h2>Admin Dashboard</h2>"

if __name__ == '__main__':
    app.run(debug=True, port=5000)
