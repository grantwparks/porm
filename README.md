porm
==

PHP ORM

Public managed properties should be designated as @porm in their docblocks.

class PormModel will orchestrate.  Call it from your base class.

class PormProperty will deal with property management.  This includes lazy loading (plus preemptive loading if you code data methods the way we tell you), value caching in session and/or memcached (this needs to be generic), access control, intelligent updates -- only the fields that were actually changed will be saved or appear in audit trail.

class PormCache manages caching of properties.  They can be cached in the session (per user) and/or in Memcache.
