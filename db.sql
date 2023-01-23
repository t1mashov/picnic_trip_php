
create table `user` (
    id int primary key auto_increment,
    `name` varchar(20),
    password_hash varchar(32)
);

create table category (
    id int primary key,
    `name` varchar(20)
);

create table item (
    id int primary key auto_increment,
    `name` varchar(50),
    category_id int,
    foreign key (category_id) references category (id)
);

create table my_item (
    `name` varchar(50),
    checked int(1),
    user_id int,
    foreign key (user_id) references user (id)
);

create table history (
    id int primary key auto_increment,
    user_id int,
    area_id int,
    comment text,
    foreign key (user_id) references user (id)
);

insert into category (id, name)
values
(1, 'Мясное'),
(2, 'Фрукты/овощи'),
(3, 'Для бутербродов'),
(4, 'Напитки'),
(5, 'Посуда'),
(6, 'Для шашлыков'),
(7, 'Прочее');

insert into item (`name`, category_id)
values
('Мясо',	1),
('Сосиски',	1),
('Томаты',	2),
('Огурцы',	2),
('Грибы',	1),
('Картофель',	2),
('Салат',	2),
('Маринованные огурцы',	2),
('Сыр',	3),
('Хлеб',	3),
('Колбаса',	3),
('Майонез',	3),
('Кетчуп',	3),
('Яблоки',	2),
('Бананы',	2),
('Апельсины',	2),
('Вода',	4),
('Чай',	4),
('Лимонад',	4),
('Чашки',	5),
('Ложки',	5),
('Вилки',	5),
('Тарелки',	5),
('Ножи',	5),
('Салфетки и бумажные полотенца',	7),
('Вода (для тушения костра)',	7),
('Мангал',	6),
('Жидкость для розжига',	6),
('Шампуры',	6),
('Решетка',	6),
('Средства от комаров и клещей',	7),
('Мешок для мусора',	7),
('Плед',	7),
('Аптечка',	7);