declare global {
  interface Window {
    __audit_data__?: string;
  }
}

interface HasLink {
  link: string;
}

export interface User extends HasLink {
  id: string;
  login: string;
  impersonated_id?: number | null;
  impersonated_login?: string | null;
}

export interface Request extends HasLink {
  ip?: string;
  log_id?: number;
  trace_id?: string;
  app?: string;
  run_id?: string;
}

export interface Diff {
  old: Record<string, any> | null;
  new: Record<string, any> | null;
}

export interface Metadata {
  enriched: {
    captured_by: string;
    received_at: string;
  };
}

export interface DataType extends HasLink {
  id: string;
  schema: string;
  table: string;
  entity_id: string;
  operation: string;
  timestamp: string;
  user: User;
  request?: Request;
  diff: Diff;
  metadata: Metadata;
  key: string;
  kind?: "group";
  rows?: DataType[];
  inGroup?: boolean;
}

export type Scope = "all" | "direct" | "nested";

export interface Filters {
  search: string;
  users: string[];
  apps: string[];
  operations: string[];
  range: [number, number] | null;
  scope: Scope;
}
