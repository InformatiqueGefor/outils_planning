# Model de base de données

```sql
Table formation {
    id integer [pk, not null, unique]
    name varchar(255) [not null, unique]
    created_at date [default: `now()`]
    updated_at date [null]
}
Table session {
  id integer [pk, not null, unique]
  name varchar(255) [not null, unique]
  start datetime [not null]
  end datetime [not null]
  theorie_hours integer [not null]
  stage_hours integer [not null]
  total_hours integer [not null]
  created_at date [default: `now()`]
  updated_at date [null]
  formation_id integer [pk, not null, unique]
}
Ref: session.formation_id < formation.id // one-to-many
Table stage {
    id integer [pk, not null, unique]
    name varchar(255) [not null]
    start datetime [not null]
    end datetime [not null]
    created_at date [default: `now()`]
    updated_at date [null]
    session_id integer [pk, not null, unique]
}
Ref: stage.session_id < session.id // one-to-many
Table conges {
  id integer [pk, not null, unique]
  start datetime [not null]
  end datetime [not null]
  created_at date [default: `now()`]
  updated_at date [null]
  session_id integer [pk, not null, unique]
}
Ref: conges.session_id < session.id // one-to-many
Table examens {
  id integer [pk, not null, unique]
  start datetime [not null]
  end datetime [not null]
  created_at date [default: `now()`]
  updated_at date [null]
  session_id integer [pk, not null, unique]
}
Ref: examens.session_id < session.id // one-to-many
```
