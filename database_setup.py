import sqlite3
import hashlib

def setup_database():
    """
    Sets up the SQLite database, creating all necessary tables and a default admin user.
    """
    conn = sqlite3.connect('database.db')
    cursor = conn.cursor()

    # Drop existing tables to ensure a clean setup
    tables_to_drop = [
        "users", "movies", "series", "seasons", "episodes",
        "servers", "genres", "movie_genres", "series_genres", "likes"
    ]
    for table in tables_to_drop:
        cursor.execute(f"DROP TABLE IF EXISTS {table};")
    print("Dropped existing tables (if any).")

    # ---- Create tables ----

    # users table
    cursor.execute("""
    CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL
    )
    """)
    print("Table 'users' created.")

    # genres table
    cursor.execute("""
    CREATE TABLE genres (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    )
    """)
    print("Table 'genres' created.")

    # movies table
    cursor.execute("""
    CREATE TABLE movies (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        tmdb_id INTEGER UNIQUE,
        title TEXT NOT NULL,
        description TEXT,
        poster_path TEXT,
        backdrop_path TEXT,
        release_date TEXT,
        rating REAL,
        duration INTEGER,
        parental_rating TEXT
    )
    """)
    print("Table 'movies' created.")

    # series table
    cursor.execute("""
    CREATE TABLE series (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        tmdb_id INTEGER UNIQUE,
        title TEXT NOT NULL,
        description TEXT,
        poster_path TEXT,
        backdrop_path TEXT,
        first_air_date TEXT,
        rating REAL,
        parental_rating TEXT
    )
    """)
    print("Table 'series' created.")

    # seasons table
    cursor.execute("""
    CREATE TABLE seasons (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        series_id INTEGER NOT NULL,
        season_number INTEGER NOT NULL,
        name TEXT,
        poster_path TEXT,
        FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
    )
    """)
    print("Table 'seasons' created.")

    # episodes table
    cursor.execute("""
    CREATE TABLE episodes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        season_id INTEGER NOT NULL,
        episode_number INTEGER NOT NULL,
        title TEXT,
        description TEXT,
        still_path TEXT,
        duration INTEGER,
        FOREIGN KEY (season_id) REFERENCES seasons(id) ON DELETE CASCADE
    )
    """)
    print("Table 'episodes' created.")

    # servers table (polymorphic)
    cursor.execute("""
    CREATE TABLE servers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        content_id INTEGER NOT NULL,
        content_type TEXT NOT NULL, -- 'movie' or 'episode'
        name TEXT NOT NULL,
        url TEXT NOT NULL,
        quality TEXT
    )
    """)
    print("Table 'servers' created.")

    # movie_genres pivot table
    cursor.execute("""
    CREATE TABLE movie_genres (
        movie_id INTEGER NOT NULL,
        genre_id INTEGER NOT NULL,
        PRIMARY KEY (movie_id, genre_id),
        FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
        FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
    )
    """)
    print("Table 'movie_genres' created.")

    # series_genres pivot table
    cursor.execute("""
    CREATE TABLE series_genres (
        series_id INTEGER NOT NULL,
        genre_id INTEGER NOT NULL,
        PRIMARY KEY (series_id, genre_id),
        FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE,
        FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
    )
    """)
    print("Table 'series_genres' created.")

    # likes table (polymorphic)
    cursor.execute("""
    CREATE TABLE likes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        content_id INTEGER NOT NULL,
        content_type TEXT NOT NULL, -- 'movie' or 'series'
        likes INTEGER DEFAULT 0,
        dislikes INTEGER DEFAULT 0,
        UNIQUE(content_id, content_type)
    )
    """)
    print("Table 'likes' created.")

    # ---- Insert initial data ----

    # Insert admin user
    username = 'admin'
    # Hash the password for security
    password = 'password'
    hashed_password = hashlib.sha256(password.encode('utf-8')).hexdigest()

    cursor.execute("INSERT INTO users (username, password) VALUES (?, ?)", (username, hashed_password))
    print("Admin user created (admin/password).")

    conn.commit()
    conn.close()

    print("\nDatabase setup completed successfully!")

if __name__ == '__main__':
    setup_database()
