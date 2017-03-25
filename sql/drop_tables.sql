-- Lisää DROP TABLE lauseet tähän tiedostoon
-- Enumit
DROP TYPE IF EXISTS rights CASCADE;
DROP TYPE IF EXISTS status CASCADE;

-- Taulut
DROP TABLE IF EXISTS work_group CASCADE;
DROP TABLE IF EXISTS person CASCADE;
DROP TABLE IF EXISTS project CASCADE;
DROP TABLE IF EXISTS task CASCADE;
DROP TABLE IF EXISTS workers_tasks CASCADE;
DROP TABLE IF EXISTS workers_groups CASCADE;