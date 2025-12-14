<?php
require_once __DIR__ . '/BaseDao.php';

class MatchesDao extends BaseDao {
    
    public function __construct() { 
        parent::__construct("matches"); 
    }

    /**
     * Override getAll() to include JOINs
     */
    public function getAll(): array {
        $query = "SELECT m.*, 
                    v.name AS venue_name,
                    hp.name AS home_team_name, 
                    ap.name AS away_team_name,
                    l.name AS league_name,
                    lp.name AS league_phase_name
                FROM matches m
                LEFT JOIN venues v ON v.id = m.venue_id
                LEFT JOIN participants hp ON hp.id = m.home_participant_id
                LEFT JOIN participants ap ON ap.id = m.away_participant_id
                LEFT JOIN league l ON l.id = m.league_id
                LEFT JOIN league_phase lp ON lp.id = m.league_phase_id
                ORDER BY m.start_time DESC";
        
        return $this->query($query, []);
    }

    /**
     * Override getById() to include JOINs
     */
    public function getById(int $id): ?array {
        $query = "SELECT m.*, 
                    v.name AS venue_name,
                    hp.name AS home_team_name, 
                    ap.name AS away_team_name,
                    l.name AS league_name,
                    lp.name AS league_phase_name
                FROM matches m
                LEFT JOIN venues v ON v.id = m.venue_id
                LEFT JOIN participants hp ON hp.id = m.home_participant_id
                LEFT JOIN participants ap ON ap.id = m.away_participant_id
                LEFT JOIN league l ON l.id = m.league_id
                LEFT JOIN league_phase lp ON lp.id = m.league_phase_id
                WHERE m.id = :id";
        
        return $this->query_unique($query, ['id' => $id]);
    }

    /**
     * Get matches by league (your existing method)
     */
    public function listByLeague(int $league_id): array {
        $query = "SELECT m.*, 
                    v.name AS venue_name,
                    hp.name AS home_team_name, 
                    ap.name AS away_team_name
                FROM matches m
                LEFT JOIN venues v ON v.id = m.venue_id
                JOIN participants hp ON hp.id = m.home_participant_id
                JOIN participants ap ON ap.id = m.away_participant_id
                WHERE m.league_id = :l
                ORDER BY m.start_time";
        
        return $this->query($query, [':l' => $league_id]);
    }

}