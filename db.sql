
create table `user` (
    id int primary key auto_increment,
    `name` varchar(20),
    password_hash varchar(32)
);

create table category (
    id int primary key auto_increment,
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
(1, 'Еда'),
(2, 'Вещи');

insert into item (`name`, category_id)
values
('Мясо',	1),
('Сосиски',	1),
('Томаты',	1),
('Огурцы',	1),
('Грибы',	1),
('Картофель',	1),
('Салат',	1),
('Маринованные огурцы',	1),
('Сыр',	1),
('Хлеб',	1),
('Яблоки',	1),
('Бананы',	1),
('Апельсины',	1),
('Вода',	1),
('Чай',	1),
('Лимонад',	1),
('Посуда',	2),
('Салфетки и бумажные полотенца',	2),
('Вода (для тушения костра)',	2),
('Мангал',	2),
('Жидкость для розжига',	2),
('Шампуры',	2),
('Решетка',	2),
('Средства от комаров и клещей',	2),
('Мешок для мусора',	2),
('Плед',	2),
('Аптечка',	2);