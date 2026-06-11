-- WPoets Full Stack Developer Test
-- Database schema and seed data

CREATE DATABASE IF NOT EXISTS wpoets_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE wpoets_db;

-- Categories (Tab titles in Column 1)
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Slides (items shown in Column 2 & 3)
CREATE TABLE IF NOT EXISTS slides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    image_url VARCHAR(500) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Seed data
INSERT INTO categories (name, sort_order) VALUES
('Architecture', 1),
('Interior Design', 2),
('Landscape', 3),
('Urban Planning', 4);

INSERT INTO slides (category_id, title, description, image_url, sort_order) VALUES
(1, 'Modern Villa', 'A stunning contemporary villa with clean geometric lines and open spaces that blur the boundary between indoors and out.', 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80', 1),
(1, 'Glass Tower', 'Soaring glass facade catching golden hour light, a landmark redefining the city skyline.', 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=800&q=80', 2),
(1, 'Brick Loft', 'Industrial heritage meets modern living in this converted warehouse space with exposed brick and steel.', 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&q=80', 3),

(2, 'Minimal Living Room', 'Carefully curated minimalism — every object earns its place in this serene, light-filled living space.', 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&q=80', 1),
(2, 'Warm Kitchen', 'Rich timber surfaces and soft pendant lighting create a kitchen designed for gathering and conversation.', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&q=80', 2),
(2, 'Master Bedroom', 'Layered textures and a muted palette turn this bedroom into a genuine retreat from the day.', 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=800&q=80', 3),

(3, 'Zen Garden', 'Raked gravel and sculpted boulders compose a meditative landscape rooted in Japanese tradition.', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80', 1),
(3, 'Coastal Path', 'A winding coastal trail frames dramatic ocean views and invites slow, purposeful movement through nature.', 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&q=80', 2),
(3, 'Rooftop Garden', 'Green infrastructure that softens the urban edge — a rooftop meadow above a busy city street.', 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&q=80', 3),

(4, 'City Square', 'A reimagined public square that restores civic life through generous seating, greenery, and human-scale design.', 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=800&q=80', 1),
(4, 'Transit Hub', 'Seamless connections between rail, bus, and pedestrian routes in a light-flooded interchange building.', 'https://images.unsplash.com/photo-1569429593410-b498b3fb3387?w=800&q=80', 2),
(4, 'Mixed Use District', 'A walkable neighbourhood where living, working, and playing happen within a five-minute radius.', 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=800&q=80', 3);
