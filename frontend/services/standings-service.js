
// === StandingsService: puni kartice i tabelu iz baze ===
const StandingsService = {
  init() {
    this.loadFromStandings()
      .catch(() => this.loadFromMatches()); // fallback
  },

  // 1) Direkt iz baze (preporučeno)
  loadFromStandings() {
    return new Promise((resolve, reject) => {
      RestClient.get('standings', (payload) => {
        const rows = Array.isArray(payload) ? payload : (payload?.data || []);

        // Očekivana polja (mapiraj ako su drugačija): 
        // team_name, played, wins, draws, losses, goals_for, goals_against, goal_diff, points, last5
        this.updateCardsFromStandings(rows);
        this.renderTable(rows);
        resolve();
      }, (xhr) => reject(xhr));
    });
  },

  // 2) Ako nema /standings, izračunaj iz /matches
  loadFromMatches() {
    RestClient.get('matches', (payload) => {
      const matches = Array.isArray(payload) ? payload : (payload?.data || []);
      const table = this.computeStandingsFromMatches(matches);
      this.updateCardsFromMatches(matches);
      this.renderTable(table);
    }, () => {
      // ne ruši UI ako ni ovo ne radi
    });
  },

  // Tabela render (koristi tvoj Utils.datatable)
  renderTable(rows) {
    const cols = [
      { data: 'team_name', title: 'Tim' },
      { data: 'played', title: 'U' },
      { data: 'wins', title: 'P' },
      { data: 'draws', title: 'N' },
      { data: 'losses', title: 'I' },
      { data: 'goals_for', title: 'GF' },
      { data: 'goals_against', title: 'GA' },
      { data: 'goal_diff', title: 'GD' },
      { data: 'points', title: 'Bodovi' },
      // (opciono) posljednjih 5: "W,D,L,..." ako backend šalje
      { data: 'last5', title: 'Forma', render: (d)=> d || '–' }
    ];
    Utils.datatable('standings-table', cols, rows, 12);
  },

  // Kartice kad imamo /standings
  updateCardsFromStandings(rows) {
    const played = rows.reduce((s,r)=> s + Number(r.played||0), 0);
    const goals  = rows.reduce((s,r)=> s + Number(r.goals_for||0), 0);
    const gpm    = played ? (goals / played).toFixed(1) : '–';

    // rounds_left: ako backend šalje "rounds_left" globalno, ovdje ga postavi;
    // ako ne, ostavi '–' ili računaj iz /matches (fallback grana to radi)
    document.getElementById('stat-played').textContent = played;
    document.getElementById('stat-goals').textContent  = goals;
    document.getElementById('stat-gpm').textContent    = gpm;
    document.getElementById('stat-rounds-left').textContent = '–';
  },

  // Kartice kad računamo iz /matches
  updateCardsFromMatches(matches) {
    const toNum = (x)=> Number.isFinite(Number(x)) ? Number(x) : 0;
    const now = new Date();
    let finished = 0, goals = 0;
    const upcomingRounds = new Set();

    for (const m of matches) {
      const status = String(m.status || '').toLowerCase();
      const done = ['finished','completed','played'].includes(status) ||
                   (m.home_score != null && m.away_score != null);

      if (done) {
        finished++;
        goals += toNum(m.home_score ?? m.home_goals ?? m.score_home)
               + toNum(m.away_score ?? m.away_goals ?? m.score_away);
      } else {
        const ts = m.start_time ? new Date(m.start_time) : null;
        if (ts instanceof Date && !isNaN(ts) && ts > now) {
          const round = m.round ?? m.match_round ?? m.kolo;
          if (round != null) upcomingRounds.add(String(round));
        }
      }
    }
    const gpm = finished ? (goals / finished).toFixed(1) : '–';
    document.getElementById('stat-played').textContent = finished;
    document.getElementById('stat-goals').textContent  = goals;
    document.getElementById('stat-gpm').textContent    = gpm;
    document.getElementById('stat-rounds-left').textContent = upcomingRounds.size || '–';
  },

  // Izračunaj tabelu iz matches (ako nema /standings)
  computeStandingsFromMatches(matches) {
    // očekujemo: home_participant_id, away_participant_id, home_score, away_score, status
    // i (po mogućnosti) home_team_name/away_team_name
    const table = new Map();

    function ensure(teamId, teamName){
      if (!table.has(teamId)) {
        table.set(teamId, {
          team_id: teamId,
          team_name: teamName || ('Tim ' + teamId),
          played: 0, wins: 0, draws: 0, losses: 0,
          goals_for: 0, goals_against: 0, goal_diff: 0, points: 0
        });
      }
      return table.get(teamId);
    }

    const toNum = (x)=> Number.isFinite(Number(x)) ? Number(x) : 0;

    for (const m of matches) {
      const status = String(m.status || '').toLowerCase();
      const done = ['finished','completed','played'].includes(status) ||
                   (m.home_score != null && m.away_score != null);
      if (!done) continue;

      const hid = m.home_participant_id;
      const aid = m.away_participant_id;
      const hn  = m.home_team_name;
      const an  = m.away_team_name;

      const hs = toNum(m.home_score ?? m.home_goals ?? m.score_home);
      const as = toNum(m.away_score ?? m.away_goals ?? m.score_away);

      const H = ensure(hid, hn);
      const A = ensure(aid, an);

      H.played++; A.played++;
      H.goals_for += hs; H.goals_against += as;
      A.goals_for += as; A.goals_against += hs;

      if (hs > as) { H.wins++; A.losses++; H.points += 3; }
      else if (hs < as) { A.wins++; H.losses++; A.points += 3; }
      else { H.draws++; A.draws++; H.points += 1; A.points += 1; }
    }

    // goal diff i sortiranje
    const rows = Array.from(table.values()).map(r => ({
      ...r,
      goal_diff: r.goals_for - r.goals_against
    }));

    rows.sort((a,b) => (
      b.points - a.points ||
      (b.goal_diff - a.goal_diff) ||
      (b.goals_for - a.goals_for) ||
      a.team_name.localeCompare(b.team_name)
    ));

    return rows;
  }
};

// Auto-start
$(document).ready(() => StandingsService.init());
