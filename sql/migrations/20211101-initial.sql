CREATE TABLE IF NOT EXISTS events (
    event_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100),
    start_time INTEGER,
    end_time INTEGER,
    description VARCHAR(10000),
    cover_image VARCHAR(1000)
);
